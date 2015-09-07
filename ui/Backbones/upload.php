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
        die(new ModelResponse(false, 'Please browse for a valid file'));
    }

    $destPath = "$uploadPath/$fileName";
    if (move_uploaded_file($fileTmpLoc, $destPath))
    {
        $raw_data = CSV::Parse($destPath);

        $userList = new Models\UserList(false);

        // proceed with creation
        foreach ($raw_data as $row)
        {
            $user = new Models\User();
            $user->Absorb($row);
            $userList->add($user);
        }

        if (!$userList->Create())
        {
            die(ModelResponse::DataSaveFailed());
        }

        $response = new ModelResponse(true, 'Data import success!', $userList);
        die($response);
    }
    else
    {
        die(ModelResponse::Busy());
    }
}
?>