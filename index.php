<?php


require_once './system/IndexEngine.php';

define( 'ROOT_PATH',

        rtrim( str_replace( '\\', '/', realpath(__DIR__) ),'/' ).'/'

);


$index = new IndexEngine();
$index->run();