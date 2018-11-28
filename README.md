# Init Projesi
Her php projesinde ihtiyaç duyulan console işlemleri için yapılmıştır.

**Temel işlevler:** 
* ilk kurulum işlemleri
* assetleri yapılandırma
* http ayarları
* dosya permission ayarları

## Kurulum

* Composer ile ekleme

    
    composer require dgncan/init

* Projenizin kendi ayarlarını injekt edebilmek için şu formatta bir dosyayı init.php adıyla kök dizine yerleştiriniz.
 
`

    <?php
    
    require_once 'src/functions.php';
    
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
                    $vendorRoot = __DIR__ . '/vendor/dgncan/adminlte-minimal';
                    $publicRoot =  __DIR__ . '/public/adminlte';
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
 `   
    
    
## Kullanım

    vendor/bin/init 

ile uygulamanın kullanılabilecek komutlarını ve yerleşik yardım dökümanını görebilirsiniz.  