<?php

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class ClearCache extends Command
{
    protected function configure()
    {
        $this->setName('cache:clear')
            ->setDescription('Clear the cache')
            ->addArgument(
                'type',
                InputArgument::OPTIONAL,
                'What cache do you want to clear? Options are template'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fs = new Filesystem();
        $cacheDir = __DIR__.'/../../cache/templates';

        $type = $input->getArgument('type');
        $types = ['template'];

        if (in_array($type, $types)) {
            $output->writeln("Removing {$type} cache files");
        }

        switch ($type) {
            case "template":
                $files = array_diff(scandir($cacheDir), ['..', '.']);
                foreach ($files as $file) {
                    $fs->remove($cacheDir.'/'.$file);
                }
                $output->writeln("<info>Successfully removed template cache files</info>");
                break;

            default:
                $output->writeln("<error>You must specify cache type</error>");
        }

    }
}