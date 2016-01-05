#!/usr/bin/php
<?php

$ignore = [
    './Test',
    './docs',
    './vendor',
    './tasks.php',
    './.idea',
    './.git'
];

$newVersion = '1.3.1';


if (!$newVersion) {
    $tags = glob('.git/refs/tags/*');

    if (count($tags) < 1)
        throw new Exception(
            "No tags found. Make sure the .git directory is there and you have any tags yet."
        );

    sort($tags);

    $lastTag = basename(end($tags));
}


$dirIterator = new RecursiveDirectoryIterator('./');
$itIterator = new RecursiveIteratorIterator($dirIterator);
$codeFiles = new RegexIterator($itIterator, '/\.php$/');

$ignore = array_map(function($val) {

    return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $val);
}, $ignore);


echo "Set Version: $newVersion\n";

//Do eeet
foreach ($codeFiles as $codeFile) {

    foreach ($ignore as $ignored) {

        if (strncmp($ignored, $codeFile, strlen($ignored)) === 0) {
            continue 2;
        }
    }

    echo "Handling $codeFile\n";

    $content = file_get_contents($codeFile);

    //Convert the file to UTF-8 always!
    $content = mb_convert_encoding($content, 'UTF-8');

    //Strip \r and \0 anywhere
    $content = str_replace(["\r", "\0"], '', $content);

    //Automatically append version to @version (auto): tags
    $content = preg_replace("/@version.*\n/iU", "@version    {$newVersion}\n", $content);

    file_put_contents($codeFile, $content);
}