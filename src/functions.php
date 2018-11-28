<?php
/**
 * init functions of helpers
 *
 * @author dogancan
 * Date/Time: 28.11.2018 11:46
 */


/**
 * @param $files
 * @param $publicRoot
 * @param $vendorRoot
 * @throws Exception
 */
function copyFile($files, $publicRoot, $vendorRoot)
{
    foreach ($files as $file) {
        $dirPath = dirname($publicRoot . $file);
        if (!file_exists($dirPath)) {
            if (!@mkdir($dirPath, 0777, true)) {
                throw new Exception("The directories could not created. dirPath:".$dirPath);
            }
        }

        if (!@file_put_contents($publicRoot . $file, file_get_contents($vendorRoot . $file))) {
            throw new Exception("The files could not copied. Source:". $vendorRoot . $file ." - Target:". $publicRoot . $file);
        }
    }
}

