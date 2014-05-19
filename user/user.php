<?php

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/includes/common.php';	

	function existUser($username){

		global $pdo;			
		
		$sql = "select count(*) from user where username='$username'";
		$result = $pdo->query($sql);
      $row = $result->fetch();
      
      return ($row[0]>0);	
	}

	function getUserIdByUsername($username){

		global $pdo;			
		
		$sql = "select id from user where username='$username'";
		$result = $pdo->query($sql);
      $row = $result->fetch();
      
      return ($row[0]);	
	}

	function updateUserPwd($username,$pwd){
	
		global $pdo;			

		return $pdo->exec("update user set password='$pwd' where username='$username'");	
	}

	function isLoginSuccessful($username,$pwd){

		global $pdo;			
		
		$sql = "select password from user where username='$username'";
		$result = $pdo->query($sql);
      $row = $result->fetch();
      if($row) {
      	return crypt($pwd, $row[0])==$row[0];
      }
      
      return false;	
	}

	function insertUser($username,$pwd) {
			
		global $pdo;
		
		$pdo->exec("insert into user (username,password) values ('$username','$pwd')");				
	}	
	
	function isAnyUserLoggedIn() {
		
		session_start();
		if(array_key_exists('username', $_SESSION)) {
			
			$username = $_SESSION['username'];			
			return !empty($username);	
		}
		else {
			return false;
		}		
	}

	function validPassword($password) {
       
       //Empty error array for the errors if any
		$error = array();
		// Password Strength check
		if( strlen($password) < 6 ) {
			$error[] = 'Password need to have at least 6 characters!';
		}
 
		if( strlen($password) > 20 ) {
			$error[] = 'Password needs to have less than 20 characters!';
		}
 
		if( !preg_match("#[0-9]+#", $password) ) {
			$error[] = 'Password must include at least one number!';
		}
 
 
		if( !preg_match("#[a-z]+#", $password) ) {
			$error[] = 'Password must include at least one letter!';
		}
 
 
		if( !preg_match("#[A-Z]+#", $password) ) {
			$error[] = 'Password must include at least one uppercase letter!';
		}
 
				// Make the array readable
				$errors=implode('<br />', $error);
				return $errors;
	}


	// define variables and set to empty values
	$username = $pwd1 = $pwd2 = $oldPwd = "";
	$usernameErr = $pwd1Err = $pwd2Err = $oldPwdErr = "";
	$pathInfo = $userAction = $message = "";
	$successValidation = $pwdUpdated = false;

	try{
		session_start();
					
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
								
				if($_POST["user_action"]=="signup") {
	
					if (empty($_POST["username"])) {
					    $usernameErr = "Username is required";
					  } 
					  else {
					    $username = test_input($_POST["username"]);
					  }
				
					if (empty($_POST["pwd1"])) {
					    $pwd1Err = "Password is required";
					  } 
					  else {
					    $pwd1 = test_input($_POST["pwd1"]);
					  }	
					  
					 if (empty($_POST["pwd2"])) {
					    $pwd2Err = "Repeat password is required";
					  } 
					  else {
					    $pwd2 = test_input($_POST["pwd2"]);
					  }
													
					$successValidation = !empty($username) && !empty($pwd1) && !empty($pwd2);
					
					if($successValidation){	
					
						$equalPwds = $pwd1==$pwd2;
						if($equalPwds){
							$pwdValidationErrors = validPassword($pwd1);
							if(empty($pwdValidationErrors)) {	
								//check if username already exists
								if(existUser($username)) {
									$message = "Username $username ja existe";
								}		
								else {
									$hashedPwd = crypt($pwd1);
									insertUser($username,$hashedPwd);
									$_SESSION['username'] = $username;
									$message = "User $username foi criado correctamente";							
								}
							}
							else {
								$message = $pwdValidationErrors;
							}
						}
						else{
							$message = "Pwd and repeat pwd must be equal";
						}									
							
						
					}	
				}
				elseif($_POST["user_action"]=="login") {
	
					if (empty($_POST["username"])) {
					    $usernameErr = "Username is required";
					  } 
					  else {
					    $username = test_input($_POST["username"]);
					  }
				
					if (empty($_POST["pwd1"])) {
					    $pwd1Err = "Password is required";
					  } 
					  else {
					    $pwd1 = test_input($_POST["pwd1"]);
					  }						  					 
				
					$successValidation = !empty($username) && !empty($pwd1);
					
					if($successValidation){
							
						//check username credentials
						if(!isLoginSuccessful($username,$pwd1)) {
							$message = "Username and/or password provided are wrong";
						}		
						else {							
							$_SESSION['username'] = $username;
							$message = "User $username logged in successfully";							
						}
					}
				}
				elseif($_POST["user_action"]=="pwd_change") {
		
					if (empty($_POST["old_pwd"])) {
					    $oldPwdErr = "Old pwd is required";
					  } 
					  else {
					    $oldPwd = test_input($_POST["old_pwd"]);
					  }
				
					if (empty($_POST["pwd1"])) {
					    $pwd1Err = "Password is required";
					  } 
					  else {
					    $pwd1 = test_input($_POST["pwd1"]);
					  }	
					  
					 if (empty($_POST["pwd2"])) {
					    $pwd2Err = "Repeat password is required";
					  } 
					  else {
					    $pwd2 = test_input($_POST["pwd2"]);
					  }						  					 
				
					$successValidation = !empty($oldPwd) && !empty($pwd1) && !empty($pwd2);
					
					if($successValidation){
						$equalPwds = $pwd1==$pwd2;
						if($equalPwds){
							$pwdValidationErrors = validPassword($pwd1);
							if(empty($pwdValidationErrors)) {
		
								//check if old pwd is ok
								if(isLoginSuccessful($_SESSION['username'],$oldPwd)) {						
									//update pwd
									$hashedPwd = crypt($pwd1);
									$num = updateUserPwd($_SESSION['username'],$hashedPwd);
									if($num>0) {
										$pwdUpdated = true;
										$message = "Password was changed successfully";
									}
									else {
										$pwdUpdated = false;
										$message = "Password could not be changed";
									}						
								}
								else {
									$message = "Old password is not right";
								}
							}
							else {
								$message = $pwdValidationErrors;
							}
					}
					else{
						$message = "Pwd and repeat pwd must be equal";
					}
				}
			}
		}				
	}
	
	catch (PDOException $e){
		$output = 'Unable to access to the database, ' . $e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'] .'/includes/error.html.php';
		exit();
	}














?>