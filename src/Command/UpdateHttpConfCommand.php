<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 27.11.2018 11:49
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateHttpConfCommand extends Command
{
    private $applicationName = '';

    private $settings = [];
    private $localConfPath = '';

    protected function configure()
    {
        $this
            ->setName('update:http')
            ->setDescription('http conf günceller')
            ->setHelp('Http conf dosyalarını environmenta göre yapılandırır..')
        ;
        $this
            ->addArgument('env', InputArgument::OPTIONAL, 'Environment tercihi. Yapılmazsa default dev dir.')
        ;
    }

    protected function setup(SymfonyStyle $io)
    {
        $initPath = __DIR__.'/../../init.php';
        if (!@file_exists($initPath)) {
            $io->error("Uygulamanızın kök dizininde init.php adında bir dosya bulunmalı. Bakınız: README.md");
            exit(1);
        }
        $this->settings = (require $initPath)['update-http-conf'];
        $this->applicationName = (require $initPath)['application-name'];

        if ($this->applicationName == "") {
            $io->error("Uygulamanızın kök dizininde yer alan init.php dosyasında `application-name'=>'PROJE_ADINIZ' `
             kaydı yer almalı.  Bakınız: README.md");
            exit(1);
        }

        $this->localConfPath = '/usr/local/httpd_docs/conf/'.$this->applicationName.'-alias.conf';
        if (isset($this->settings['localConfPath']) && $this->settings['localConfPath'] !=='') {
            $this->localConfPath = $this->settings['localConfPath'];
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('update-http-conf');

        $this->setup($io);

        $env = $input->getArgument('env') =='' ? 'dev' : $input->getArgument('env');

        $io->section("Conf dosyası yerine konuyor");
        switch ($env) {
            case 'dev':
                $putPath = $this->localConfPath.'-alias.conf';
                break;
            case 'test':
                $putPath = '/workloc/okulistik-test/conf/'.$this->applicationName.'-alias.conf';
                break;
            case 'preprod':
                $putPath = '/workloc/okulistik-preprod/conf/'.$this->applicationName.'-alias.conf';
                break;
            case 'prod':
                $putPath = '/work/okulistik/conf/'.$this->applicationName.'-alias.conf';
                break;
            default:
                $io->error("Ortam seçmelisiniz");
                exit(1);
                break;
        }
        try {
            $getPath = __DIR__ . '/../conf/'.$env.'.'.$this->applicationName.'-alias.conf';
            if ($get = !@file_get_contents($getPath)) {
                throw new \Exception('Dosya okunamıyor: '.$getPath);
            }
            if (!@file_put_contents($putPath, $get)) {
                throw new \Exception('Dosya yazılamıyor: '.$putPath);
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            exit(1);
        }
        $io->success("Alias file path: " . $putPath);


        $io->section(" Restart Apache");
        if ($env == 'dev') {
            $cmd = "/usr/local/httpd_docs/bin/apachectl -k restart";
            system($cmd, $r);
            if ($r == 1) {
                $io->error("Apache restart olmadı");
                exit(1);
            }
        } else {
            $io->success("!Sadece dev environment için");
        }
    }
}
