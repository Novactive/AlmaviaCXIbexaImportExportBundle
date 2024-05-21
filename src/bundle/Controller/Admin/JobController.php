<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExportBundle\Controller\Admin;

use AlmaviaCX\Bundle\IbexaImportExport\Job\Form\JobCreateFlow;
use AlmaviaCX\Bundle\IbexaImportExport\Job\Job;
use AlmaviaCX\Bundle\IbexaImportExport\Job\JobService;
use AlmaviaCX\Bundle\IbexaImportExport\Writer\WriterRegistry;
use Ibexa\Contracts\AdminUi\Controller\Controller;
use Ibexa\Contracts\AdminUi\Notification\TranslatableNotificationHandlerInterface;
use Ibexa\Contracts\Core\Repository\PermissionResolver;
use Ibexa\Core\Base\Exceptions\NotFoundException;
use Ibexa\Core\MVC\Symfony\Security\Authorization\Attribute;
use Pagerfanta\Adapter\CallbackAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarExporter\Instantiator;
use Twig\Environment;
use Twig\Error\LoaderError;

class JobController extends Controller
{
    protected FormFactoryInterface $formFactory;
    protected TranslatableNotificationHandlerInterface $notificationHandler;
    protected JobService $jobService;
    protected JobCreateFlow $jobCreateFlow;
    protected PermissionResolver $permissionResolver;
    protected Environment $twig;
    protected WriterRegistry $writerRegistry;

    public function __construct(
        FormFactoryInterface $formFactory,
        TranslatableNotificationHandlerInterface $notificationHandler,
        JobService $jobService,
        JobCreateFlow $jobCreateFlow,
        PermissionResolver $permissionResolver,
        Environment $twig,
        WriterRegistry $writerRegistry
    ) {
        $this->formFactory = $formFactory;
        $this->notificationHandler = $notificationHandler;
        $this->jobService = $jobService;
        $this->jobCreateFlow = $jobCreateFlow;
        $this->permissionResolver = $permissionResolver;
        $this->twig = $twig;
        $this->writerRegistry = $writerRegistry;
    }

    public function listAction(Request $request): Response
    {
        $page = $request->query->get('page') ?? 1;

        $pagerfanta = new Pagerfanta(
            new CallbackAdapter(
                function (): int {
                    return $this->jobService->countJobs();
                },
                function (int $offset, int $length): array {
                    return $this->jobService->loadJobs($length, $offset);
                }
            )
        );

        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

        return $this->render('@ibexadesign/import_export/job/list.html.twig', [
            'pager' => $pagerfanta,
            'can_create' => $this->isGranted(new Attribute('import_export', 'job.create')),
        ]);
    }

    public function createAction(Request $request): Response
    {
        $job = Instantiator::instantiate(Job::class);
        $this->jobCreateFlow->bind($job);

        $form = $this->jobCreateFlow->createForm();
        if ($this->jobCreateFlow->isValid($form)) {
            $this->jobCreateFlow->saveCurrentStepData($form);
            if ($this->jobCreateFlow->nextStep()) {
                // form for the next step
                $form = $this->jobCreateFlow->createForm();
            } else {
                $this->jobCreateFlow->reset();
                try {
                    $job->setCreatorId($this->permissionResolver->getCurrentUserReference()->getUserId());
                    $this->jobService->createJob($job);
                    $this->notificationHandler->success(
                    /* @Desc("Job '%label%' created.") */
                        'job.create.success',
                        ['%label%' => $job->getLabel()],
                        'importexport'
                    );

                    return new RedirectResponse($this->generateUrl('import_export.job.view', [
                        'id' => $job->getId(),
                    ]));
                } catch (\Exception $exception) {
                    $this->notificationHandler->error(
                    /* @Ignore */
                        $exception->getMessage()
                    );
                }
            }
        }

        return $this->render('@ibexadesign/import_export/job/create.html.twig', [
            'form_job_create' => $form->createView(),
            'form_job_create_flow' => $this->jobCreateFlow,
        ]);
    }

    public function viewAction(Job $job): Response
    {
        $results = [];
        foreach ($job->getWriterResults() as $index => $writerResults) {
            try {
                $writerIdentifier = $writerResults['writerIdentifier'];
                $writer = $this->writerRegistry->get($writerIdentifier);
                $template = sprintf('@ibexadesign/import_export/writer/results/%s.html.twig', $writerIdentifier);
                $this->twig->load($template);

                $results[] = [
                    'template' => $template,
                    'parameters' => [
                        'results' => $writerResults,
                        'writerIndex' => $index,
                        'writer' => $writer,
                        'job' => $job,
                    ],
                ];
            } catch (LoaderError|NotFoundException $e) {
                continue;
            }
        }

        return $this->render('@ibexadesign/import_export/job/view.html.twig', [
            'job' => $job,
            'results' => $results,
        ]);
    }

    public function processAction(Job $job): Response
    {
        $this->jobService->runJob($job);

        return new RedirectResponse($this->generateUrl('import_export.job.view', [
            'id' => $job->getId(),
        ]));
    }
}
