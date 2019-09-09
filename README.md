# Init

Init is a console application that helps to make for php projects first configuration and publish etc.

## Key Features
* Update Assets
* Setting ini file
* Updating http conf
* Setting file permissions
* Runs sample tasks.

## Installation

To install Init by using Composer:
```bash
    $ composer require dgncan/init "^0.6"
```

Example composer.json file:
```bash
    {
      "require": {
        "dgncan/init": "^0.6",
      }
    }
```

## Usage

Create an init.php file with the following contents:

```php
    <?php
    
    require_once __DIR__.'/vendor/dgncan/init/src/functions.php';
    
    return  [
        'application-name'=>'example-project',  // required
        'update-assets'=>
        [   // sample items. look example-project folder for detail example 
            'Update adminlte-minimal'=>[
                'vendor'=>'/vendor/dgncan/adminlte-minimal',
                'public'=>'/public/adminlte',
                'files' => [
                    '/dist/css/AdminLTE.min.css',
                    '/dist/css/skins/_all-skins.min.css',
                ]
            ]
        ],
        'update-tasks'=>
        [
            'Sample dummy process'=>
                function ($args) {
                    print_r($args);
                    echo "sample dummy processed\n";
                },
            /*
              //for sample use only.      
            'minify css/custom-style.css'=>
                function () {
                    $cmd = "php vendor/tubalmartin/cssmin/cssmin -i " . getcwd() . "/public/css/custom-style.css -o " . getcwd() . "/public/css/custom-style.min.css -R";
                    system($cmd, $r);
                    if ($r == 1) {
                        throw new Exception("Fail cmd:" . $cmd);
                    }
                }
            */                    
        ],
        'update-http-conf'=>
        [
            'confPath'=> [
                'local'=>'.',                     // optional for example: /usr/local/httpd_docs/conf/
                'test'=>'/work/test/conf/',       // optional
                'preprod'=>'/work/preprod/conf/', // optional
                'prod'=>'/work/prod/conf/'        // optional
            ]
        ],
        'prod-ini-file' => '/sensitive-data-location-path/init/prod.ini', // optional
        'permission' =>
        [
            'chown'=>'www.www', // optional
            'chmod'=>'755'      // optional
        ]
    ];
```    

Or to create empty init.php :
```bash
     vendor/bin/init init 
 ```
    
## Example Project
Please look example-project folder to see example project skeleton.
