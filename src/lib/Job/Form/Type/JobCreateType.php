<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Job\Form\Type;

use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobCreateType extends AbstractType
{
    protected WorkflowRegistry $workflowRegistry;

    public function __construct(WorkflowRegistry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \AlmaviaCX\Bundle\IbexaImportExport\Job\Job $job */
        $job = $options['data'];
        switch ($options['flow_step']) {
            case 1:
                $builder->add('label', TextType::class, [
                    'label' => 'job.label',
                ]);

                $availableWorkflows = $this->workflowRegistry->getAvailableWorkflows();
                $builder->add('workflowIdentifier', ChoiceType::class, [
                    'label' => 'job.workflowIdentifier',
                    'choices' => array_flip($availableWorkflows),
                ]);
                break;
            case 2:
                $worflow = $this->workflowRegistry->get($job->getWorkflowIdentifier());

                $optionsForm = $builder->create('options', FormType::class, [
                    'label' => 'job.options',
                ]);
                $worflow->mapJobForm($optionsForm);
                $builder->add($optionsForm);

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
