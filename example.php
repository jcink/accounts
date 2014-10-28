<?php

/***************************************************
 * Account System (PHP + MySQL)
 * Login, Registration, and Groups
 * Example Usage / Demo
 *
 * @author      John Cuppi
 * @code        http://github.com/jcink/accounts
 * @license     http://unlicense.org/UNLICENSE
 * @version     1.0
 * @updated     4:55 PM Tuesday, October 28, 2014
 * @description A very simple secure account system 
 *
 ****************************************************/
 
$example = new Example();

class Example {	
public $acct;

	public function __construct() {
	
	require("accounts.php");
	
	$this->acct    = new Accounts();

	if(empty($_GET['act'])) {
		$_GET['act'] = '';
	}
	
	switch($_GET['act']) {
		case "login":
			$this->login();
			break;
		case "logout":
			$this->acct->logout();
			break;
		case "register":
			$this->register();
			break;	
		}
				
	require("template.php");
	
	}
	
	/**
	* Send $_POST content to the login function
	*
	* @return void
	*/
	
	public function login() {
		if(isset($_POST['name']) and isset($_POST['password'])) {
			$login = $this->acct->login($_POST['name'], $_POST['password']);
			
			if(!empty($this->acct->login_errors)) {
				$this->print_errors( $this->acct->login_errors );
			}
		
		}
	}
	
	/**
	* Send $_POST content to the registration function
	*
	* @return void
	*/
	
	public function register() {
		$this->acct->register($_POST['name'], $_POST['password'], $_POST['email']);
		
		if(!empty($this->acct->reg_errors)) {
			$this->print_errors( $this->acct->reg_errors );
		} else {
			print "<div id='content'>Registration successful!</div>";
		}
	}
	
	/**
	* Errors are stored as an array, this loops and prints them.
	*
	* @return void
	*/
	
	public function print_errors( $errors ) {
	$error_messages = '';
	
		if(!empty($errors)) {
			foreach ($errors as $key=>$val) {
				$error_messages .= "$val <br />";
			}
		}
		
		print "<div id='content'>{$error_messages}</div>";
	}
	
	/**
	* Logged in as function, showing all account data printed.
	*
	* @return String/HTML
	*/
	
	public function logged_in_as() {
	if( !empty($this->acct->sess()->id) ) {
	
		$account_data = "<div id='content'>";
		foreach($this->acct->sess() as $key=>$val) {
			if(!is_numeric($key)) {
				$account_data .= "<b>$key</b>: $val <br />";
			}
		}
		$account_data .= "</div>";
		
		return("<div id='content' class='center'>
				[ Logged In as {$this->acct->sess()->name} | <a href='example.php?act=logout'>Log Out</a> ] <br />
				</div>
				{$account_data}");
					
	}
	}
}

?>
