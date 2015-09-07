<?php

// Check if there's a pending upload
if (isset($_FILES["myfile"]))
{
	// Upload path
	$uploadPath = sprintf("%s/uploads", ROOT_PATH);
	
	// Check if upload path exists
	if (!file_exists($uploadPath))
		mkdir($uploadPath);
	
	$fileName = $_FILES["myfile"]["name"]; //file name
	$fileTmpLoc = $_FILES["myfile"]["tmp_name"]; //file in the php tmp folder
	$fileType = $_FILES["myfile"]["type"]; //file type
	$fileSize = $_FILES["myfile"]["size"]; //file size
	$fileErrorMsg = $_FILES["myfile"]["error"]; //0 for false and 1 for true
	if (!$fileTmpLoc)//if file not chosen
	{
		echo "Error: Please browse for a file";
		exit();
	}
	if (move_uploaded_file($fileTmpLoc, "$uploadPath/$fileName"))
	{
		echo "$fileName upload is complete";
	}
	else 
	{
		echo "move_uploaded_file function failed";
	}
	
	die();
}
?>