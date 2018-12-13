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
function copyFiles($files, $publicRoot, $vendorRoot)
{
    foreach ($files as $file) {
        $targetPath = getcwd() . $publicRoot . $file;
        $targetDirPath = dirname($targetPath);
        if (!file_exists($targetDirPath)) {
            if (!@mkdir($targetDirPath, 0777, true)) {
                throw new Exception("The directories could not created. Target dirPath:".$targetDirPath);
            }
        }
        $sourcePath = getcwd() . $vendorRoot . $file;
        if (!@file_put_contents($targetPath, file_get_contents($sourcePath))) {
            throw new Exception("The files could not copied. Source:". $sourcePath ." - Target:". $targetPath);
        }
    }
}

