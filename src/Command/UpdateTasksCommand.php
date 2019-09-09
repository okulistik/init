<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 11.01.2019 10:01
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateTasksCommand extends Command
{
    private $applicationName = '';

    private $functions = [];

    protected function configure()
    {
        $this
            ->setName('update:tasks')
            ->setDescription('Runs functions written as closure.')
            ->setHelp('Runs functions written as closure.')
        ;
        $this
            ->addArgument('env', InputArgument::OPTIONAL, 'if not selected, it is Dev as a default.')
        ;
    }

    protected function setup(SymfonyStyle $io)
    {
        $initPath = getcwd().'/init.php';
        if (!@file_exists($initPath)) {
            $io->error("It must be the init.php file in root folder. Look: README.md");
            exit(1);
        }
        $settings = include $initPath;
        $this->functions = $settings['update-tasks'];
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == "") {
            $io->error("It must write project name in the init.php file. For example: `application-name'=>'PROJECT_NAME' `
             Look: README.md");
            exit(1);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('update-tasks');

        $this->setup($io);

        $env = $input->getArgument('env') =='' ? 'dev' : $input->getArgument('env');
        $args = ['env'=>$env];

        foreach ($this->functions as $name => $function) {
            $io->section($name);
            try {
                $function($args);
            } catch (\Exception $e) {
                $io->error($e->getMessage());
                exit(1);
            }
            $io->success('ok');
        }
    }
}
