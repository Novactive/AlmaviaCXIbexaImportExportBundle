<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job\Form\Type;

use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobFormType extends AbstractType
{
    protected WorkflowRegistry $workflowRegistry;

    public function __construct(WorkflowRegistry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var \AlmaviaCX\Bundle\IbexaImportExport\Job\Job $job */
        switch ($options['flow_step']) {
            case 1:
                $builder->add('label', TextType::class, [
                'label' => 'job.label',
                ]);

                $availableWorkflows = $this->workflowRegistry->getAvailableWorkflows();

                $builder->add(
                    $builder->create('workflowIdentifier', ChoiceType::class, [
                    'label' => /* @Desc("Workflow") */ 'job.workflowIdentifier',
                    'choices' => array_map(function (string $fqn) {
                        return addslashes($fqn);
                    }, array_flip($availableWorkflows)),
                    ])
                    ->addModelTransformer(
                        new CallbackTransformer(
                            function ($fqn) {
                                return $fqn ? addslashes($fqn) : null;
                            },
                            function ($fqn) {
                                return $fqn ? stripslashes($fqn) : null;
                            }
                        )
                    )
                );
                break;
            case 2:
                $builder->add('options', FormType::class, [
                'label' => /* @Desc("Workflow options") */ 'workflow.options',
                ]);

                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
                                    'translation_domain' => 'forms',
                                ]);
    }
}
