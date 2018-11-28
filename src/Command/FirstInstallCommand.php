<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 27.11.2018 12:18
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FirstInstallCommand extends Command
{
    private $applicationName = '';

    protected function configure()
    {
        $this
            ->setName('update:ini')
            ->setDescription('İlk kurulumu yapar. environment settingsleri lokalize eder.')
            ->setHelp('env klasörlerindeki ini dosyalarından environment a göre seçip yerleştirir.')
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
        $this->applicationName = (require $initPath)['application-name'];

        if ($this->applicationName == "") {
            $io->error("Uygulamanızın kök dizininde yer alan init.php dosyasında `application-name'=>'PROJE_ADINIZ' `
             kaydı yer almalı.  Bakınız: README.md");
            exit(1);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('first-install');

        $this->setup($io);

        $env = $input->getArgument('env') =='' ? 'dev' : $input->getArgument('env');

        $getPath = dirname(__DIR__) . '/env/'.$env.'.ini';
        # production environment ini si jenkins sunucusunun içinde yer alır.
        # Root Path = /var/lib/jenkins/workspace-prod-ini/  her projenin kendi ismiyle klasörü vardır.
        # Bu klasöre ve sunucu giriş yetkisi sadece sistem yöneticisinde
        if ($env == 'prod') {
            $getPath = '/var/lib/jenkins/workspace-prod-ini/'.$this->applicationName.'/prod.ini';
        }

        if (!file_exists($getPath)) {
            $io->error('env klasöründe istenen env dosyasi yok');
            exit(1);
        }

        try {
            if (!$get = @file_get_contents($getPath)) {
                throw new \Exception('Dosya okunamıyor: '.$getPath);
            }
            $putPath = dirname(__DIR__)."/conf/settings-local.ini";
            if (!@file_put_contents($putPath, $get)) {
                throw new \Exception('Dosya yazılamıyor: '.$putPath);
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            exit(1);
        }
        $io->success('');
    }
}
