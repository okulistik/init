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

    private $prodIniFilePath = '';

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
        $initPath = getcwd().'/init.php';
        if (!@file_exists($initPath)) {
            $io->error("Uygulamanızın kök dizininde init.php adında bir dosya bulunmalı. Bakınız: README.md");
            exit(1);
        }
        $settings = include $initPath;
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == '') {
            $io->error("Uygulamanızın kök dizininde yer alan init.php dosyasında `application-name'=>'PROJE_ADINIZ' `
             kaydı yer almalı.  Bakınız: README.md");
            exit(1);
        }

        $this->prodIniFilePath = $settings['prod-ini-file'];
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('first-install');

        $this->setup($io);

        $env = $input->getArgument('env') =='' ? 'dev' : $input->getArgument('env');

        $getPath = getcwd() . '/env/'.$env.'.ini';

        # It's different because of prod environment includes sensitive data.
        if ($env == 'prod' && $this->prodIniFilePath != '') {
            $getPath = $this->prodIniFilePath;
        }

        if (!file_exists($getPath)) {
            $io->error('There is no the ini file in "env" directory. getPath:'.$getPath);
            exit(1);
        }

        try {
            $get = @file_get_contents($getPath);
            if (!$get) {
                throw new \Exception('The ini file could not read : "'.$getPath.'"');
            }

            $confDirPath = getcwd() . "/conf";
            if (!file_exists($confDirPath)) {
                if (!@mkdir($confDirPath, 0777, true)) {
                    throw new \Exception("The conf directory could not created. confDirPath:".$confDirPath);
                }
            }

            $putPath = getcwd() . "/conf/settings-local.ini";
            if (!@file_put_contents($putPath, $get)) {
                throw new \Exception('The ini file could not write: '.$putPath);
            }
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            exit(1);
        }
        $io->success('');
    }
}
