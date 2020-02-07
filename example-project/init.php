<?php

require_once __DIR__.'/vendor/dgncan/init/src/functions.php';

return  [
    'application-name'=>'example-project',
    'update-assets'=>
        [
            'Update adminlte-minimal'=>[
                'vendor'=>'/vendor/dgncan/adminlte-minimal',
                'public'=>'/public/adminlte',
                'files' => [
                    '/dist/css/AdminLTE.min.css',
                    '/dist/css/skins/_all-skins.min.css',
                    '/bower_components/bootstrap/dist/css/bootstrap.min.css',
                    '/bower_components/font-awesome/css/font-awesome.min.css',
                    '/bower_components/font-awesome/fonts/fontawesome-webfont.woff2',
                    '/bower_components/font-awesome/fonts/fontawesome-webfont.woff',
                    '/bower_components/font-awesome/fonts/fontawesome-webfont.ttf',
                    '/dist/img/avatar5.png',
                    '/bower_components/jquery/dist/jquery.min.js',
                    '/bower_components/bootstrap/dist/js/bootstrap.min.js',
                    '/dist/js/adminlte.min.js'
                ]
            ]
        ],
    'update-tasks'=>
        [
            'Sample dummy process'=>
                function ($args) {
                    print_r($args);
                    echo "sample dummy processed\n";
                }
        ],
    'update-http-conf'=>
        [
            'confPath'=>[
                'local'=>'/work/conf/',
                'test'=>'/work/test/conf/',
                'preprod'=>'/work/preprod/conf/',
                'prod'=>'/work/prod/conf/'
            ]
        ],
    'prod-ini-file' => '/var/lib/jenkins/workspace-prod-ini/init/prod.ini',
    'permission' =>
        [
            'chown'=>'www.www',
            'chmod'=>'755'
        ]
];
