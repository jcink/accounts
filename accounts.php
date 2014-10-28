<?php

/***************************************************
 * Account System (PHP + MySQL)
 * Login, Registration, and Groups
 * Core Library
 *
 * @author      John Cuppi
 * @code        http://github.com/jcink/accounts
 * @license     http://github.com/jcink/license
 * @version     1.0
 * @updated     4:51 PM Tuesday, October 28, 2014
 * @description A very simple secure account system 
 *
 ****************************************************/

class Accounts
{	
	public $table_prefix    = "";
	public $sessions        = true;
	public $bind_session_ip = false; 
	public $cookies         = false;
	public $hash_type       = PASSWORD_BCRYPT;
	public $db;
	
	/**
	* Set up database variables. 
	*
	* @return void
	*/
	
	public function __construct() {
		
		if($this->cookies AND $this->sessions) {
			throw new Exception('Please enable only sessions or cookies.');
		}
		
		$pdo_config = array();
		
		$pdo_config['cfg']		   = 'mysql:host=127.0.0.1;dbname=accounts';		
		$pdo_config['db_user']     = 'root';
		$pdo_config['database']    = 'root';
		
		if( $this->sessions ) {
			session_start();
		}
		
		try {
			$this->db = new PDO($pdo_config['cfg'], $pdo_config['db_user'], $pdo_config['database']);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
		} catch (PDOException $e) {
			throw new Exception('Could not connect to database.');
		}
	}
	
	/**
	* Return an object of all the data we have in the database for the
	* current logged in user based on cookies.
	*
	* @return object True if a user is logged in successfully.
	*/
	
	public function sess() {	
	
		$account_id = 0;
		$account_password = '';
	
		// Set cookie data, if we're using cookies
		if($this->cookies) {
			if( isset($_COOKIE['id']) AND isset($_COOKIE['password'])) {
				$account_id = $_COOKIE['id'];
				$password   = $_COOKIE['password'];
			}
		}
		
		// Set session data, if we're using sessions
		if($this->sessions) {
			if( isset($_SESSION['id']) AND isset($_SESSION['password'])) {
				$account_id = $_SESSION['id'];
				$password   = $_SESSION['password'];
				
				// Bind this session to an IP?
				if($this->bind_session_ip) {
					if($_SERVER['REMOTE_ADDR'] != $_SESSION['ip_address']){
						return false;
					}
				}
			}		
		}
		
		// As long as either sessions or cookies are
		// used, and data is present, we can get
		// "logged in" account information
		
		if(!empty($account_id) AND !empty($password)) { 
			$query = $this->db->prepare("SELECT * FROM {$this->table_prefix}accounts a
								   LEFT JOIN {$this->table_prefix}groups g ON a.group_id = g.g_id
								   WHERE a.id=:id");
								   
			$query->execute(array(":id" => intval($account_id)));
			$member = $query->fetch();
		
			// Verify member password matches session/cookie password
			if(empty($member['id']) AND empty($member['password'])) {
				return false;
			}
			
			// Check member password against session/cookie var
			if($member['password'] != $password) {
				return false;
			}
			
			// sha1 key for form submission use
			// to prevent CSRF in applications	
			$member['form_key'] = sha1($member['id'] . $member['email'] . $member['password']);	
		} else {
			return false;
		}

		if(!empty($member['id'])) {
			return (object) $member;
		}
	}

	/**
	* Log in with a valid name and password
	* and create a new session (if we want sessions)
	*
	* @return bool True if there are no registration errors.
	*/	
	
	public function register( $name, $password, $email = "", $group_id = 2 ) {

		// Check if an account exists.
		if(strlen($name) < 1) {
			$this->reg_errors['name_short'] = 'Usernames must be at least two characters.';
		}

		// Check if an account exists.
		if($this->name_exists($name) OR strlen($name) > 64) {
			$this->reg_errors['name_exists'] = 'The username you have chosen already exists.';
		}
	
		// Validate and hash the password.
		if(strlen($password) > 72 || strlen($password) < 3) {
			$this->reg_errors['password_too_long'] = "The password you have chosen was too short or too long.";
		}
	
		$hashed_password = password_hash($password, $this->hash_type);
	
		// Check if the email is valid.	
		if(!empty($email)) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) OR strlen($email) > 64) {
				$this->reg_errors['email_invalid'] = 'The email you entered was not valid.';		
			}
		} else {
			$this->reg_errors['email_exists'] = 'You did not enter an email address.';
		}
		
		// Check if the email was already used
		if($this->email_exists( $email )) {
			$this->reg_errors['email_exists'] = 'The email you entered already exists.';
		}
		
		// No errors? Create account.
		if(empty($this->reg_errors)) {
		
			$name  = filter_var($name, FILTER_SANITIZE_STRING);
			$email = filter_var($email, FILTER_SANITIZE_EMAIL);
			
			$query = $this->db->prepare("INSERT INTO {$this->table_prefix}accounts VALUES ('', :name, :password, :email, :group_id, '".time()."', '0', '', :r_addr);");
			
			$query->execute(array(":name" => $name, 
								  ":password" => $hashed_password, 
								  ":email" => $email, 
								  ":group_id" => $group_id, 
								  ":r_addr" => $_SERVER['REMOTE_ADDR']));
			return true;
		} else {
			return false;
		}
		
	}
	
 	/**
	* Check if an account exists by name, not case sensitive
	*
	* @return boolean True if an email was found.
	*/
	
	public function name_exists( $name ) {
	
		$query = $this->db->prepare("SELECT id FROM {$this->table_prefix}accounts WHERE name=:name");
		$query->execute(array(":name" => $name));
		$member = $query->fetch();
			
		if(!empty($member['id'])) {
			return true;
		}
		
	}

 	/**
	* Check if an email exists, not case sensitive
	*
	* @return boolean True if an email was found.
	*/
	
	public function email_exists( $email ) {
   		
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$query = $this->db->prepare("SELECT email FROM {$this->table_prefix}accounts WHERE email=:email");
			$query->execute(array(":email" => $email));
			$member = $query->fetch();
		}
			
		if(!empty($member['email'])) {
			return true;
		}
		
	}
	
	/**
	* Log in with a valid name and password
	* and create a new session (if we want sessions)
	*
	* @return boolean True if login was successful.
	*/	
	
	public function login( $name, $password, $remember_me = 0 ) {
	
		$name  = filter_var($name, FILTER_SANITIZE_STRING);
		
		$query = $this->db->prepare("SELECT id, password FROM {$this->table_prefix}accounts WHERE name=:name");
		$query->execute(array(":name" => $name));
		$member = $query->fetch();
		
		if(empty($member['id'])) {
			$this->login_errors['no_exist'] = "The name you entered, $name, does not exist.";
		}
	
		// Does the password entered match the hash in the database?
		// Successful login...				
		if (password_verify($password, $member['password']) AND !empty($member['id'])) {
				
			// Set a cookie with name/password
			// Cookies are set as HTTP-Only to mitigate XSS
			
			if($this->cookies) {
				setcookie('id', $member['id'], null, '/', null, null, true);
				setcookie('password', $member['password'], null, '/', null, null, true);
				
				$_COOKIE['id']       = $member['id'];
				$_COOKIE['password'] = $member['password'];
			}
	
			// Set up a session with the user id and password
			// Create a session variable for IP address for
			// tying sessions to IPs
			
			if($this->sessions) {
				$_SESSION['id'] 	    = $member['id'];
				$_SESSION['password']   = $member['password'];
				$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
			}
			
			return true;
		} else {
			$this->login_errors['pass_incorrect'] = "The password you entered was incorrect.";
			return false;
		}
		
	}
	
  	/**
	* Check if an account exists by name (string) OR id number.
	*
	* @return array True if a member account was found w/ all data.
	*/
	
	public function get_account( $input ) {

		if(is_numeric($input)) {
			$query = $this->db->prepare("SELECT * FROM {$this->table_prefix}accounts WHERE name=:name");
			$query->execute(array(":name" => $input));
		} else {
			$query = $this->db->prepare("SELECT * FROM {$this->table_prefix}accounts WHERE id=:id");
			$query->execute(array(":id" => $input));
		}
		
		$member = $query->fetch();
			
		if(!empty($member['id'])) {
			return $member;
		}
		
	} 
	
	/**
	* Log out / delete cookies and sessions
	*
	* @return void
	*/	
	
	public function logout() {
	
		if($this->cookies) {
			setcookie('id', null, null, '/', null, null, true);
			setcookie('password', null, null, '/', null, null, true);
		
			unset($_COOKIE['id']);
			unset($_COOKIE['password']);	
		}
		
		if($this->sessions) {
			session_unset();
		}
		
	}	
}
	
?>