<?php		

session_start(); 
include("myfunctions.php");
include("account.php");

( $dbh = mysql_connect ( $hostname, $username, $password ) )
       or die ( "Unable to connect to MySQL database" );	
print "Connected to MySQL<br><br>";
mysql_select_db( $project ); 

if($_SESSION["state"] != "user" && !$_SESSION["logged_in"])
{
	redirect("INVALID ATTEMPT TO ACCESS A PROTECTED SCRIPT. Redirecting to login form. One moment..." , "login.html");
}

echo "Current State: {$_SESSION["state"]} <br><br>";
echo "Greetings {$_SESSION["name"]} <br>";
echo "Current Balance = \${$_SESSION["current_balance"]} <br>";
	 
	/** 
    at bottom of the page after PHP section it has the 
        HTML for the deposit & withdraw features
	**/
?>

	
<form action = "transact.php">

<fieldset style = "background : #ffff00; width : 70%;"  > <legend align = "center"> Transaction  Form </legend>

Amount <input type = text name = "amount"  id = "amount"
	autocomplete = "off"
	placeholder  = "Enter amount"
	autofocus    = "on"
	><br><br>

Deposit  	<input type = radio name = "type" id = "D" value = "D"><br>
Withdraw 	<input type = radio name = "type" id = "W" value = "W"><br>
Mail Copy?	<input type = checkbox name = "mailrequest"> <br>

<input type = submit ><br>

</fieldset>

</form>
 