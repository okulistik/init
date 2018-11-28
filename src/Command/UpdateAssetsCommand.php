<?php
/**
 * init
 *
 * @author dogancan
 * Date/Time: 27.11.2018 10:18
 */

namespace Init\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateAssetsCommand extends Command
{
    private $applicationName = '';

    private $functions = [];

    protected function configure()
    {
        $this
            ->setName('update:assets')
            ->setDescription('Assetstleri günceller')
            ->setHelp('Assetsleri günceller. vendor klasörlerinden public te ilgili yerlere yerleştirir. 
            minified işlemleri yapar.')
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
        $this->functions = $settings['update-assets'];
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
        $io->title('update-assets');

        $this->setup($io);

        foreach ($this->functions as $name => $function) {
            $io->section($name);
            try {
                $function();
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }
            $io->success('ok');
        }
    }
}
