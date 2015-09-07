<?php

//$path = ROOT_PATH . '/assets/tests/employee_details.csv';
//$contents = file_get_contents($path);

//die(str_replace(PHP_EOL, '<br>', CSV::EscapeCSV($contents)));

//print_r(CSV::Parse($path));

$monthlyIndividual = new \Models\Reports\MonthlyIndividualList();
die(new ModelResponse(true, 'Success', $monthlyIndividual));


?>