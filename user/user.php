<?php

	include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';

	function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

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

		$pdo->exec("update user set password='$pwd' where username='$username'");	
	}

	function isLoginSuccessful($username,$pwd){

		global $pdo;			
		
		$sql = "select count(*) from user where username='$username' and password='$pwd'";
		$result = $pdo->query($sql);
      $row = $result->fetch();
      
      return ($row[0]>0);	
	}

	function insertUser($username,$pwd) {
			
		global $pdo;
		
		$pdo->exec("insert into user (username,password) values ('$username','$pwd')");				
	}	
	
	function isUserAuthorised() {
		
		session_start();
		$username = $_SESSION['username'];
			
		return !empty($username);			
	}

	// define variables and set to empty values
	$username = $pwd1 = $pwd2 = $oldPwd = "";
	$usernameErr = $pwd1Err = $pwd2Err = $oldPwdErr = "";
	$userAction = $message = "";
	$successValidation = false;

	try{
		session_start();
		$username = $_SESSION['username'];
				
		
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
							
						//check if username already exists
						if(existUser($username)) {
							$message = "Username $username ja existe";
						}		
						else {
							insertUser($username,$pwd1);
							$_SESSION['username'] = $username;
							$message = "User $username foi criado correctamente";							
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
							$message = "Login of user $username failed";
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

						//check if old pwd is ok
						if(isLoginSuccessful($username,$oldPwd)) {						
							//update pwd
							updateUserPwd($username,$pwd1);
							$message = "Password was changed successfully";
						}
						else {
							$message = "Old password is not right";
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