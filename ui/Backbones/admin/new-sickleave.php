<?php

if (!\Utilities\Session::IsLoggedIn())
    header('location: ' . \Utilities\System::GetBaseURL());

?>