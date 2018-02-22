<?php
session_start(); 
include ("myfunctions.php");
include ("account.php");

( $dbh = mysql_connect ( $hostname, $username, $password ) )
       or die ( "Unable to connect to MySQL database" );	
print "Connected to MySQL<br><br>";
mysql_select_db( $project ); 

echo "Current State: {$_SESSION["state"]} <br><br>";

//INPUT
$amount = $_GET["amount"];
$type   = $_GET["type"];
$mymail = $_GET["mailrequest"]; 

//PROTECTION
$type = get_case($type, $amount);

if($_SESSION["state"] == "user" && $_SESSION["logged_in"] == true)
{
	update($_SESSION["name"], $amount, $type);
}

else
{
	redirect("INVALID ATTEMPT TO ACCESS A PROTECTED SCRIPT. Redirecting to login form. One moment..." , "login.html");
}


$type_user = 'U';
sql($type_user, $_SESSION["name"], $s1, $s2);

$result1 = get_A($s1); 
$result2 = get_T($type, $s2);
$msg = "";
$msg .= "<br><br>ACCOUNTS: $result1 <br>";
$msg .= "<br>TRANSACTIONS: $result2<br><br>";
echo $msg;
				
//MAIL FEATURE
if (isset($mymail))
{			
	myMail($type_user, $_SESSION["name"], $msg);		
} 

if(!isset($mymail))
{
	print "Copy not mailed";
}

print "<br><br>This interaction is completed.<br><br>"
		
?>

<a href="https://web.njit.edu/~crm45/download/ass02/user.php">Click here to return to user script<br></a>

<a href="https://web.njit.edu/~crm45/download/ass02/logout.php">Click here to logout</a>



