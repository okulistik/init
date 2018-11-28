<?php

require_once __DIR__.'/vendor/dgncan/init/src/functions.php';

return  [
    'application-name'=>'init',
    'update-assets'=>
    [
        'Sample dummy process'=>
            function () {
                echo "sample dummy processed\n";
            },
        'Update adminlte-minimal'=>
            function () {
                $vendorRoot = getcwd() . '/vendor/dgncan/adminlte-minimal';
                $publicRoot =  getcwd() . '/public/adminlte';
                $files = [
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
                    '/dist/js/adminlte.min.js',
                ];
                copyFile($files, $publicRoot, $vendorRoot);
            }
    ],
    'update-http-conf'=>
    [
        'localConfPath'=>'', //optional
    ]
];
