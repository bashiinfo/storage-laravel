<?php
require_once './vendor/autoload.php';
use Bashi\CloudStorage;
$storage = new CloudStorage('http://upfiledev.h5more.com', 'education_abc', 'testAppKey');

$options = [
    'file_name'=>'1_4.png',
    'type'=>'',
    'path'=>''
];

$rel = $storage->upload(fopen('./1_4.png', 'r'),$options);
print_r($rel);
// Array
// (
//     [code] => 0
//     [msg] => success
//     [data] => Array
//     (
//         [hash] => FuNh6GRc8J__GsN3vPoarykmuo2y
//         [key] => education/1_4.png
//         [domains] => Array
//         (
//             [0] => bskjtestqn.h5more.com
//             )
        
//         )
    
//     )