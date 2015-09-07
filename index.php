<?php


require_once './system/IndexEngine.php';

define( 'ROOT_PATH',
        rtrim( str_replace( '\\', '/', realpath(__DIR__) ),'/' ).'/'
);

define( 'APPLICATION_INI',
        rtrim( ROOT_PATH . 'config/application.ini' )
);


$index = new IndexEngine();
$index->run();