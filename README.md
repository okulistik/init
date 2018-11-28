# Init
Her php projesinde ihtiyaç duyulan console işlemleri için yapılmıştır.

**Temel işlevler:** 
* ilk kurulum işlemleri
* assetleri yapılandırma
* http ayarları

## Kurulum

* Örnek composer.json dosyası

```bash
    {
      "require-dev": {
        "dgncan/init": "dev-master",
        "dgncan/adminlte-minimal":"dev-master"
      }
    }
```

* Projenizin kendi ayarlarını injekt edebilmek için şu formatta bir dosyayı init.php adıyla kök dizine yerleştiriniz.
 
```php
    <?php
    
    require_once __DIR__.'/vendor/dgncan/init/src/functions.php';
    
    return  [
        'application-name'=>'example-project',
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
            'confPath'=>[
                'local'=>'.', // for example: /usr/local/httpd_docs/conf/
                'test'=>'/work/test/conf/',
                'preprod'=>'/work/preprod/conf/',
                'prod'=>'/work/prod/conf/'
            ]
        ],
        'prod-ini-file' => '/var/lib/jenkins/workspace-prod-ini/init/prod.ini'
    ];
```    
    
## Kullanım
Kullanılabilecek komutlarını ve yerleşik yardım dökümanını görmek için:

```bash
    vendor/bin/init 
```

Örnek proje iskeleti için tests/example-project klasörüne bakınız. 