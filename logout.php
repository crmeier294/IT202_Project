<?php

session_start(); 
include("myfunctions.php");
include("account.php");

session_unset();	//destroy session variables
session_destroy();	//destroys session
redirect("Logging out. One moment please.", "login.html");	//return to login.html

?>