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
            ->setDescription('Dosya ve dizin yetkilerini günceller.')
            ->setHelp('Dosya ve dizin yetkilerini belirlenen kullanıcıya göre düzenler.')
        ;
    }

    protected function setup(SymfonyStyle $io)
    {
        $initPath = getcwd().'/init.php';
        if (!@file_exists($initPath)) {
            $io->error("Uygulamanızın kök dizininde init.php adında bir dosya bulunmalı. Bakınız: README.md");
            exit(1);
        }
        $settings = include $initPath;
        $this->chown = $settings['permission']['chown'];
        $this->chmod = $settings['permission']['chmod'];
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == "") {
            $io->error("Uygulamanızın kök dizininde yer alan init.php dosyasında `application-name'=>'PROJE_ADINIZ' `
             kaydı yer almalı.  Bakınız: README.md");
            exit(1);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('permissions');

        try {
            system('chown -R '.$this->chown.' '. getcwd() . '/');
            system('chmod -R '.$this->chmod.' '. getcwd() . '/');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            exit(1);
        }

        $io->success('');
    }
}
