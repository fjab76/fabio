<?php

$msg_campoObrigatorio = "<p><span class='error'>* campo obrigatorio.</span></p>";
$msg_campoObrigatorioLiteral = "campo obrigatorio.";

//user handling
$msg_privilegeCreateNewUser = "O usuário não tem privilégio para criar um novo usuário";
$msg_usernameReq  = "Username is required";
$msg_pwd1Req = "Password is required";
$msg_pwd2Req  = "Repeat password is required";
$msg_existingUser = "O usuário '$' já existe";
$msg_newUserCreated = "A conta do usuário '$' foi criada corretamente";
$msg_pw2Wrong = "'Confirma nova senha' está errada";
$msg_accountLocked = "Por motivos de segurança, sua conta '$' foi bloqueada";
$msg_loginDataWrong = "Os dados de acesso estão errados";
$msg_userLoggedIn = "O usuário % foi logado corretamente";
$msg_pwdChanged = "Senha foi alterada corretamente";
$msg_pwdNotChanged = "Senha não pôde ser alterada";
$msg_oldPwdWrong = "Senha antiga está errada";


function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
}

//error handler function
function customError($errno, $errstr) {
  echo "";
}

//set error handler
set_error_handler("customError");

