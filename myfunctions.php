<?php

function choice($name, $pass, $amount, $type)
	{
		//checks from data and stops or returns an OK type
		echo "<br>Executed choice.";
		return $type;	
	}

function admin($name, $pass)
	{	
		//checks credentials or stops
		
		if($name != "admin" || $pass != "007")
		{
			redirect("Invalid Credentials. Redirecting to login form..." , "login.html");
		}
		
		if($name == "admin" && $pass == "007")
		{
			//LOGIN: DEFINE the $_SESSION variables
			$_SESSION[ "logged_in" ] = true;
			$_SESSION[ "state" ] = "admin";
			redirect("Valid Credentials. Redirecting to admin script..." , "admin.php");
		}
	} 
	
function user($name, $pass)  
	{
		//EXIT BAD IF:
		//1. user name or password credentials invalid (ie not in a row of the accounts table)		
		//2. else continue session
		
		$t = mysql_query("select * from Accounts where user = '$name' and pass = '$pass'");
		
		//1.
		if (mysql_num_rows($t) == 0)
			{
				redirect("Invalid Credentials. Redirecting to login form..." , "login.html");
			}
		
		//2.
		else
		{
			//LOGIN:   DEFINE the $_SESSION variables
			$_SESSION[ "logged_in" ] = true;
			$_SESSION[ "state" ]  = "user";
			
			//STORE name and current_balance IN $_SESSION
			while ( $r = mysql_fetch_array($t) )
			{
				$_SESSION["current_balance"] = $r["current_balance"];
				$_SESSION["name"] = $r["user"];
			}
			
			
			redirect("Valid Credentials. Redirecting to User Script...", "user.php");
		}
	}

	
	
function update($name, $amount, $type)
	{
		//1. SQL insert transaction into T
		//2. SQL update of current_balance in A
		
		//insert in T
		if($type == 'D')
		{
			//insert into T
			$s = "insert into Transactions values( '$name', '$type', '$amount', NOW() )" ;
			echo "<br>insert SQL is: $s";
			( $t = mysql_query($s) ) or die ( mysql_error() );
			
			
			//update current_balance in Accounts
			$s = "update Accounts set current_balance = {$_SESSION["current_balance"]} + '$amount' where user = '$name'";
			echo "<br>insert SQL is: $s";
			( $t = mysql_query($s) ) or die ( mysql_error() );
		}

		//insert in T
		if($type == 'W')
		{
			$s = "insert into Transactions values( '$name', '$type', '$amount', NOW() )" ;
			echo "<br>insert SQL is: $s";
			( $t = mysql_query($s) ) or die ( mysql_error() );


			//update current_balance in Accounts
			$s = "update Accounts set current_balance = {$_SESSION["current_balance"]} - '$amount' where user = '$name'";
			echo "<br>insert SQL is: $s";
			( $t = mysql_query($s) ) or die ( mysql_error() );
		}
		
	}
	

function sql($type, $name, &$s1, &$s2)
	{
		//IF IT'S AN administrator REQUEST, THE $S1 IS DEFINED ONE WAY.
		if ($type == 'A')
		{
			$s1 = "select * from Accounts";
			$s2 = "select * from Transactions";
		}
		
		//IF IT'S A CUSTOMER (W or D) REQUEST, ONE USES A DIFFERENT $S1 QUERY:
		if ($type != 'A')
		{
			$s1 = "select * from Accounts where user = '$name' ";
			$s2 = "select * from Transactions where user = '$name' ";
		}
	}

//function File
function get_A ( $s1 ) 
	{
		$out =  "";
		($t  = mysql_query($s1)) or die (  mysql_error() );
		
		while ( $r = mysql_fetch_array($t) ) 
		{
			$user     = $r["user"];
			$passwd   = $r["pass"];
			$addr     = $r["address"];
			$curr_bal = $r["current_balance"];
			$out     .= "<br>user is $user";
			$out     .= "<br>password is $passwd";
			$out     .= "<br>address is $addr";
			$out     .= "<br>current balance is \$$curr_bal<br>";
		}
		return $out;
	}
	
	
function get_T($type, $s2)
	{
		($t  = mysql_query($s2)) or die (  mysql_error() );
		$out = "";
		
		//if A: return all T database tables
		if($type = 'A')
		{
			while ( $r = mysql_fetch_array($t) ) 
			{
				$user   = $r["user"];
				$typ    = $r["type"];
				$amount = $r["amount"];
				$date   = $r["date"];
				$out   .= "<br>user: $user \t type: $typ \t amount: $amount \t date: $date";
			}
			echo "<br>";
			return $out;
		}
		
		//if not A: return T database table associated with specified user
		if($type != 'A')
		{
			$s2 = "show * from Transactions where user = '$name'";
		}
	}


function get_mail_address($type, $name)
	{
		//if type = A, return hardcode admin email
		if($type == 'A')
		{
			return "joe_123@mailinator.com";
		}
		
		//if type != A, return user email from Accounts
		if($type != 'A')
		{
			$s = "select email from Accounts where user = '$name'";
			($t  = mysql_query($s)) or die (  mysql_error() );
            $r = mysql_fetch_array($t);
			return $r["email"];

		}
	}

function myMail($type, $name, $message)
	{
		
		echo "<br>mailed successfully";
		$to = get_mail_address($type, $name);
		$subject = "crm45";
		//$message = get_A, get_T
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		mail ($to, $subject, $message, $headers);
	}
	
function get_case($type, $amount)
	{
		//Type is neither 'D' or 'W' 
		if($type != 'D' && $type != 'W')
		{
			redirect("<br><br>Invalid Input: Type Not Specified. Please re-enter", "user.php");
		}
		
		//Type is 'D' and Amount is empty  
		if($type == 'D' && $amount == "")
		{
			redirect("<br><br>Invalid Input: Selected amount is empty. Please re-enter", "user.php");
		}
   
		//Type is 'W' and Amount is empty  
		if($type == 'W' && $amount == "")
		{
			redirect("<br><br>Invalid Input: Selected amount is empty. Please re-enter", "user.php");
		}
		
		//Type is 'D' and Amount is not numeric  (use PHP is_numeric boolean function)
		if($type == 'D' && ( !is_numeric($amount) ) )
		{
			redirect("<br><br>Invalid Input: Amount is not a numerical value. Please re-enter", "user.php");
		}
		
		//Type is 'D' and Amount is negative
		if($type == 'D' && $amount < 0)
		{
			redirect("<br><br>Invalid Input: Amount cannot be a negative value. Please re-enter", "user.php");
		}
		
		//Type is 'W' and Amount is not numeric  (use PHP is_numeric boolean function)
		if($type == 'W' && ( !is_numeric($amount) ) )
		{
			redirect("<br><br>Invalid Input: Amount is not a numerical value. Please re-enter", "user.php");
		}
		
		//Type is 'W' and Amount is negative
		if($type == 'W' && $amount < 0)
		{
			redirect("<br><br>Invalid Input: Amount cannot be a negative value. Please re-enter", "user.php");

		}
		
		if(($type == 'W') && ($amount > $_SESSION["current_balance"])) 
		{   
			redirect("<br><br>Overdraw Error. Re-enter amount.", "user.php" );
		}
		
		return $type;
		
	}

function get_type ($type, $name, $pass) 
	{		
		//Type is neither 'A' or 'U' 
		if($type != 'A' && $type != 'U')
		{
			redirect("Invalid Input: Type Not Specified. Redirecting to login form...", "login.html");
		}
		
		//Name or Pass is empty    
		if($name == "" || $pass == "")
		{
			redirect("Invalid Input: Name or Password invalid. Redirecting to login form...", "login.html");
		}
	    return $type; 
    }
  
function redirect($message, $url) 
	{
	   echo $message;   
	   header ("refresh:2; url = $url");   
	   exit();    
	}

function gatekeeper($type)  
	{	
		//REDIRECT TO LOGIN.HTML IF INVALID ATTEMPT TO ACCESS A PROTECTED SCRIPT
		if(!isset($_SESSION["logged_in"]))
		{
			redirect( "INVALID ATTEMPT TO ACCESS A PROTECTED SCRIPT. Redirecting to login form. One moment..." , "login.html");
		}
		
		if($_SESSION["state"] != $type)
		{
			redirect( "INVALID ATTEMPT TO ACCESS A PROTECTED SCRIPT. Redirecting to login form. One moment..." , "login.html");
		}	
	}
		

?>