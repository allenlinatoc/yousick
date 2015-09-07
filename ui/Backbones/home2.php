<?php

header('Content-type: application/json');

$data = [
	[
		'employee' => 'Allen',
		'age' => 20,
		'company' => 'OpeniT Asia'
	],
	[
		'employee' => 'Allesdfn',
		'age' => 20,
		'company' => 'OpeniT Inc.'
	],
	[
		'employee' => 'fsdfdskllen',
		'age' => 20,
		'company' => 'OpeniT Norge AS'
	],
	[
		'employee' => 'Jon',
		'age' => 20	,
		'company' => 'OpeniT PH'
	]
];

$result = [
	'success' => true,
	'data' => $data
];

echo json_encode($result);

?>