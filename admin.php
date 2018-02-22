<?php		

session_start(); 
include("myfunctions.php");
include("account.php");

( $dbh = mysql_connect ( $hostname, $username, $password ) )
       or die ( "Unable to connect to MySQL database" );	
print "Connected to MySQL<br><br>";
mysql_select_db( $project ); 


echo "Logged in as {$_SESSION["state"]}";

gatekeeper("admin");	//redirects you if you shouldn't be here

$type_admin = 'A';
sql($type_admin, $_SESSION["name"], $s1, $s2);

$result1 = get_A($s1); 
$result2 = get_T($type, $s2);
$msg = "";
$msg .= "<br><br>ACCOUNTS: $result1 <br>";
$msg .= "<br>TRANSACTIONS: $result2<br><br>";
echo $msg;

//MAIL FEATURE			
myMail($type_admin, $_SESSION["name"], $msg);		


print "<br><br>This interaction is completed.<br><br>"

?> 

<a href="https://web.njit.edu/~crm45/download/ass02/logout.php">Click here to logout</a>