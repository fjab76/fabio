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

	function isUserAccountDisabled($username){

		global $pdo;			
		
		$sql = "select num_attempts,max_num_attempts from user where username='$username'";
		$result = $pdo->query($sql);
      $row = $result->fetch();
      if($row) {
      	return $row['num_attempts']>=$row['max_num_attempts'];
      }
      
      return false;	
	}

	function incrementNumLoginAttempts($username) {
			
		global $pdo;
		
		$pdo->exec("update user set num_attempts=num_attempts+1 where username='$username'");				
	}

	function resetNumLoginAttempts($username) {
			
		global $pdo;
		
		$pdo->exec("update user set num_attempts=0 where username='$username'");				
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
			$error[] = 'Senhas devem ter al menos 6 caracteres';
		}
 
		if( strlen($password) > 20 ) {
			$error[] = 'Senhas devem ter menos de 20 caracteres';
		}
 
		if( !preg_match("#[0-9]+#", $password) ) {
			$error[] = 'Senhas devem ter al menos um número';
		}
 
 
		if( !preg_match("#[a-z]+#", $password) ) {
			$error[] = 'Senhas devem ter al menos uma letra';
		}
 
 
		if( !preg_match("#[A-Z]+#", $password) ) {
			$error[] = 'Senhas devem ter al menos uma letra maiúscula';
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
					
					if(($_SESSION['username'] != "fabio")) {
						$message = "O usuário não tem privilégio para criar um novo usuário";
					}
					else {
	
						if (empty($_POST["username"])) {
						    $usernameErr = $msg_campoObrigatorioLiteral;
						  } 
						  else {
						    $username = test_input($_POST["username"]);
						  }
					
						if (empty($_POST["pwd1"])) {
						    $pwd1Err = $msg_campoObrigatorioLiteral;
						  } 
						  else {
						    $pwd1 = test_input($_POST["pwd1"]);
						  }	
						  
						 if (empty($_POST["pwd2"])) {
						    $pwd2Err = $msg_campoObrigatorioLiteral;
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
										$message = "O usuário '$username' já existe";
									}		
									else {
										$hashedPwd = crypt($pwd1);
										insertUser($username,$hashedPwd);
										$message = "A conta do usuário '$username' foi criada corretamente";							
									}
								}
								else {
									$message = $pwdValidationErrors;
								}
							}
							else{
								$message = "'Confirma nova senha' está errada";
							}									
						}	
					}
				}
				elseif($_POST["user_action"]=="login") {
	
					if (empty($_POST["username"])) {
					    $usernameErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $username = test_input($_POST["username"]);
					  }
				
					if (empty($_POST["pwd1"])) {
					    $pwd1Err = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $pwd1 = test_input($_POST["pwd1"]);
					  }						  					 
				
					$successValidation = !empty($username) && !empty($pwd1);
					
					if($successValidation){
							
						if(isUserAccountDisabled($username)){
							$message = "Por motivos de segurança, sua conta '$username' foi bloqueada";
						}	
						else {										
							//check username credentials
							if(!isLoginSuccessful($username,$pwd1)) {
								$message = "Os dados de acesso estão errados";
								incrementNumLoginAttempts($username);
							}		
							else {							
								$_SESSION['username'] = $username;
								$message = "O usuário '{$_SESSION['username']}' foi logado corretamente";
								resetNumLoginAttempts($username);						
							}
						}
					}
				}
				elseif($_POST["user_action"]=="pwd_change") {
		
					if (empty($_POST["old_pwd"])) {
					    $oldPwdErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $oldPwd = test_input($_POST["old_pwd"]);
					  }
				
					if (empty($_POST["pwd1"])) {
					    $pwd1Err = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $pwd1 = test_input($_POST["pwd1"]);
					  }	
					  
					 if (empty($_POST["pwd2"])) {
					    $pwd2Err = $msg_campoObrigatorioLiteral;
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
										$message = "Senha foi alterada corretamente";
									}
									else {
										$pwdUpdated = false;
										$message = "Senha não pôde ser alterada";
									}						
								}
								else {
									$message = "Senha antiga está errada";
								}
							}
							else {
								$message = $pwdValidationErrors;
							}
					}
					else{
						$message = "'Confirma nova senha' está errada";
					}
				}
			}
		}				
	}
	
	catch (PDOException $e){
		$output = 'Base de dados não pode ser conectada, ' . $e->getMessage();
		include $_SERVER['DOCUMENT_ROOT'] .'/includes/error.html.php';
		exit();
	}














?>