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

class PermissionCommand extends Command
{
    private $applicationName = '';

    private $chown = '';
    private $chmod = '';

    protected function configure()
    {
        $this
            ->setName('update:permission')
            ->setDescription('set to permission for file and folder.')
            ->setHelp('set to permission for file and folder.')
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
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == "") {
            $io->error("It must write project name in the init.php file. For example: `application-name'=>'PROJECT_NAME' `
             Look: README.md");
            exit(1);
        }

        if (isset($settings['permission']['chown']) && $settings['permission']['chown'] !=='') {
            $this->chown = $settings['permission']['chown'];
        }
        if (isset($settings['permission']['chmod']) && $settings['permission']['chmod'] !=='') {
            $this->chmod = $settings['permission']['chmod'];
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('permissions');

        $this->setup($io);

        $io->section('chown');
        $retval = null;
        $command = 'chown -R '.$this->chown.' '. getcwd() . '/';
        $return = system($command, $retval);
        if ($retval !== 0) {
            $io->error($return.' command: '.$command);
            exit(1);
        }
        $io->success('OK');

        $io->section('chmod');
        $retval = null;
        $command = 'chmod -R '.$this->chmod.' '. getcwd() . '/';
        $return = @system($command, $retval);
        if ($retval !== 0) {
            $io->error($return.' command: '.$command);
            exit(1);
        }

        $io->success('OK');
    }
}
