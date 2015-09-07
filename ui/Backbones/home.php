<?php

//$path = ROOT_PATH . '/assets/tests/employee_details.csv';
//$contents = file_get_contents($path);

//die(str_replace(PHP_EOL, '<br>', CSV::EscapeCSV($contents)));

//print_r(CSV::Parse($path));

//die();


$user = new Models\User();
$user->Absorb([
    'username' => 'alinatoc',
    'name' => 'Allen Linatoc',
    'department' => 'Technical Trainee',
    'email' => 'alinatoc@openit.com'
]);

echo (string)$user;

//$users = new Models\UserList();
//echo $users;

die();
//echo new ModelResponse(true, '', $user);


//$user->SaveAll();
//echo $user;

//$users = new \Models\UserList(true);
//$user = \Models\User::Find(2, \Models\User::TABLE);
//$response = new ModelResponse(true, $user);
//
//echo $response;
//die();

//$p = new Phar(ROOT_PATH . 'core.phar');
//$p->extractTo(ROOT_PATH . 'core');


?>