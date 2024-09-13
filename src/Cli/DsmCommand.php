<?php

declare(strict_types=1);

namespace Mihaeu\PhpDependencies\Cli;

use Mihaeu\PhpDependencies\Formatters\DependencyStructureMatrixHtmlFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DsmCommand extends BaseCommand
{
    /** @var DependencyStructureMatrixHtmlFormatter */
    private $dependencyStructureMatrixHtmlFormatter;

    public function __construct(DependencyStructureMatrixHtmlFormatter $dependencyStructureMatrixFormatter)
    {
        parent::__construct('dsm');

        $this->defaultFormat = 'html';
        $this->allowedFormats = [$this->defaultFormat];

        $this->dependencyStructureMatrixHtmlFormatter = $dependencyStructureMatrixFormatter;
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Generate a Dependency Structure Matrix of your dependencies')
            ->addOption(
                'format',
                null,
                InputOption::VALUE_REQUIRED,
                'Output format.',
                'html'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = $input->getOptions();
        $this->ensureSourcesAreReadable($input->getArgument('source'));
        $this->ensureOutputFormatIsValid($options['format']);

        $output->write($this->dependencyStructureMatrixHtmlFormatter->format(
            $this->dependencies,
            $this->postProcessors
        ));

        return 0;
    }
}
