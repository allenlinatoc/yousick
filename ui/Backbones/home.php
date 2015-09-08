<?php

//$path = ROOT_PATH . '/assets/tests/employee_details.csv';
//$contents = file_get_contents($path);

//die(str_replace(PHP_EOL, '<br>', CSV::EscapeCSV($contents)));

//print_r(CSV::Parse($path));

if (\Utilities\Session::IsLoggedIn())
{
    $sessData = \Utilities\Session::Initialize();
    if ($sessData instanceof Models\SessionData)
    {
        if ($sessData->getUser()->isAdmin())
        {
            header('location: ' . BASE_URL . 'admin/');
        }
        else
        {
            header('location: ' . BASE_URL . 'user/');
        }
    }
}


?>