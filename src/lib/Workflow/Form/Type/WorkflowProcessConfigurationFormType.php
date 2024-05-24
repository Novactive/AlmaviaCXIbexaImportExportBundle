<?php

declare(strict_types=1);

namespace AlmaviaCX\Bundle\IbexaImportExport\Workflow\Form\Type;

use AlmaviaCX\Bundle\IbexaImportExport\Job\Job;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowProcessConfiguration;
use AlmaviaCX\Bundle\IbexaImportExport\Workflow\WorkflowRegistry;
use ReflectionClass;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkflowProcessConfigurationFormType extends AbstractType
{
    protected WorkflowRegistry $workflowRegistry;

    public function __construct(WorkflowRegistry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Job $job */
        $job = $options['job'];
        $config = $this->workflowRegistry->getWorkflowDefaultConfiguration($job->getWorkflowIdentifier());
        if (!$config) {
            return;
        }

        $readerFormType = $this->getComponentOptionsFormType($config->getReader()['type']);
        if ($readerFormType) {
            $readerForm = $builder->create('reader', FormType::class, [
                'label' => false,
            ]);
            $readerForm->add('options', $readerFormType, [
                'label' => $this->getComponentName($config->getReader()['type']),
            ]);
            $builder->add($readerForm);
        }

        $processorsForm = $builder->create('processors', FormType::class, [
            'label' => false,
        ]);
        foreach ($config->getProcessors() as $index => $processorConfig) {
            $processorFormType = $this->getComponentOptionsFormType($processorConfig['type']);
            if ($processorFormType) {
                $processorForm = $processorsForm->create($index, FormType::class, [
                    'label' => false,
                ]);
                $processorForm->add('options', $processorFormType, [
                    'label' => $this->getComponentName($processorConfig['type']),
                ]);
                $processorsForm->add($processorForm);
            }
        }
        $builder->add($processorsForm);
    }

    /**
     * @throws \ReflectionException
     */
    protected function getComponentOptionsFormType(string $componentClassName): ?string
    {
        $componentClass = new ReflectionClass($componentClassName);

        return $componentClass->getMethod('getOptionsFormType')->invoke(null);
    }

    /**
     * @throws \ReflectionException
     *
     * @return string|\Symfony\Component\Translation\TranslatableMessage
     */
    protected function getComponentName(string $componentClassName)
    {
        $componentClass = new ReflectionClass($componentClassName);

        return $componentClass->getMethod('getName')->invoke(null);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->define('job')->required()->allowedTypes(Job::class);
        $resolver->setDefaults([
                                    'data_class' => WorkflowProcessConfiguration::class,
                                ]);
    }
}
