<?php

//$path = ROOT_PATH . '/assets/tests/employee_details.csv';
//$contents = file_get_contents($path);

//die(str_replace(PHP_EOL, '<br>', CSV::EscapeCSV($contents)));

//print_r(CSV::Parse($path));


echo \Utilities\String::Format('Hello there {{name}} your age is {{age}}'
        , [
            'name' => 'Allen Linatoc',
            'age' => 10
        ]);
die();


?>