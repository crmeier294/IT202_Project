<?php		

session_start(); 
include ("myfunctions.php");
include ("account.php");

( $dbh = mysql_connect ( $hostname, $username, $password ) )
       or die ( "Unable to connect to MySQL database" );	
print "Connected to MySQL<br><br>";
mysql_select_db( $project ); 

//GET FORM DATA: INPUT
$name   = $_GET["user"];
$pass   = $_GET["pass"];
$type   = $_GET["type"];  

echo "CREDENTIALS<br>name: $name<br> pass: $pass<br> type: $type<br>";

//$type = choice($name, $pass, $amount, $type);     //data makes sense, or stop
$type = get_type($type, $name, $pass ); 		 


//admin choice
if($type == 'A')
{
    admin($name, $pass);  
}	  

//user choice
if($type != 'A')
{
    user($name, $pass);	
}	 
?> 