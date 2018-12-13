<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 28.11.2018 09:55
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('creates the init file in the root folder.')
            ->setHelp('creates the init file in the root folder.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('init.php');

        $initPath = getcwd().'/init.php';
        if (@file_exists($initPath)) {
            $io->error("There is an already init file in the root folder.");
            exit(1);
        }

        $getPath = __DIR__.'/../../init.php.dist';
        $get = @file_get_contents($getPath);
        if (!$get) {
            throw new \Exception('The init.php.dist file could not read : "' . $getPath . '"');
        }
        if (!@file_put_contents($initPath, $get)) {
            throw new \Exception('The init.php file could not write: '.$initPath);
        }

        $io->success('');
    }
}
