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

    private $confPathLocal = '.';
    private $confPathTest = '.';
    private $confPathPreprod = '.';
    private $confPathProd = '.';

    protected function configure()
    {
        $this
            ->setName('update:http')
            ->setDescription('update http alias conf')
            ->setHelp('update http alias conf.')
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
        $this->settings = $settings['update-http-conf'];
        $this->applicationName = $settings['application-name'];

        if ($this->applicationName == '') {
            $io->error("It must write project name in the init.php file. For example: `application-name'=>'PROJECT_NAME' `
             Look: README.md");
            exit(1);
        }

        if (isset($this->settings['confPath']['local']) && $this->settings['confPath']['local'] !='') {
            $this->confPathLocal = $this->settings['confPath']['local'];
        }
        if (isset($this->settings['confPath']['test']) && $this->settings['confPath']['test'] !='') {
            $this->confPathTest = $this->settings['confPath']['test'];
        }
        if (isset($this->settings['confPath']['preprod']) && $this->settings['confPath']['preprod'] !='') {
            $this->confPathPreprod = $this->settings['confPath']['preprod'];
        }
        if (isset($this->settings['confPath']['prod']) && $this->settings['confPath']['prod'] !='') {
            $this->confPathProd = $this->settings['confPath']['prod'];
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('update-http-conf');

        $this->setup($io);

        $env = $input->getArgument('env') =='' ? 'dev' : $input->getArgument('env');

        $io->section("Apache conf file will put its right place");
        switch ($env) {
            case 'dev':
                $putPath = $this->confPathLocal.'/'.$this->applicationName.'-alias.conf';
                break;
            case 'test':
                $putPath = $this->confPathTest.'/'.$this->applicationName.'-alias.conf';
                break;
            case 'preprod':
                $putPath = $this->confPathPreprod.'/'.$this->applicationName.'-alias.conf';
                break;
            case 'prod':
                $putPath = $this->confPathProd.'/'.$this->applicationName.'-alias.conf';
                break;
            default:
                $io->error("Please choose environment");
                exit(1);
                break;
        }

        try {
            $getPath = getcwd() . '/conf/'.$env.'.'.$this->applicationName.'-alias.conf';
            $get = @file_get_contents($getPath);
            if (!$get) {
                throw new \Exception('The conf file could not read. getPath: "'.$getPath.'"');
            }

            $confDirPath = getcwd() . "/conf";
            if (!file_exists($confDirPath)) {
                if (!@mkdir($confDirPath, 0777, true)) {
                    throw new \Exception('The conf directory could not created. confDirPath: "'.$confDirPath.'"');
                }
            }

            if (!@file_put_contents($putPath, $get)) {
                throw new \Exception('The alias conf file could not write. putPath: "'.$putPath.'"');
            }

        } catch (\Exception $e) {
            $io->error($e->getMessage());
            exit(1);
        }
        $io->success("Alias file path: " . $putPath);


        $io->section("Restart Apache for Development Environment:");
        if ($env == 'dev') {
            $io->success("Please restart Apache as manuel. If you use Docker restart your docker container.");
        } else {
            $io->success("Only dev");
        }
    }
}
