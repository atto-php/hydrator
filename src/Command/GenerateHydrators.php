<?php

declare(strict_types=1);

namespace Atto\Hydrator\Command;

use Atto\CodegenTools\ClassDefinition\PHPClassDefinitionProducer;
use Atto\CodegenTools\CodeGeneration\PHPFilesWriter;
use Atto\Hydrator\HydratorProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'atto:hydrator:generate',
    description: 'Generates hydrators based on your code',
)]
final class GenerateHydrators extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument(
                'source_directory',
                InputArgument::REQUIRED,
                'Directory containing the source objects you want to generate hydrators for'
            )->addOption(
                'namespace_prefix',
                null,
                InputArgument::OPTIONAL,
                'Common namespace prefix for your code, this will be replaced by the hydrator namespace prefix',
                ''
            )->addOption(
                'hydrator_namespace_prefix',
                null,
                InputArgument::OPTIONAL,
                'Namespace prefix for generated hydrator classes',
                'Generated\\Hydrator'
            )->addOption(
                'psr4_namespace_prefix',
                null,
                InputArgument::OPTIONAL,
                'PSR4 namespace prefix for the output directory',
                'Generated'
            )->addArgument(
                'output_directory',
                InputArgument::REQUIRED,
                'Directory to write generated files to'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directoryContainingFilesToGenerateFor = $input->getArgument('source_directory');
        $namespacePrefixForClasses = $input->getOption('namespace_prefix');
        $namespaceForGeneratedClasses = $input->getOption('hydrator_namespace_prefix');
        $outputDirectory = $input->getArgument('output_directory');
        $outputPsr4Prefix = $input->getOption('psr4_namespace_prefix');

        (new PHPFilesWriter($outputDirectory, $outputPsr4Prefix))->writeFiles(
            new PHPClassDefinitionProducer(
                (new HydratorProvider(
                    $directoryContainingFilesToGenerateFor,
                    $namespacePrefixForClasses,
                    $namespaceForGeneratedClasses
                ))->provideFile())
        );

        return Command::SUCCESS;
    }
}