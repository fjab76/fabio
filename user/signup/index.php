<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Pedido - Amazon Trading</title>
		
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/css/custom-theme/jquery-ui-1.10.4.custom.min.css" /> 
	<link rel="stylesheet" type="text/css" href="/css/debug.css" />
	<!--[if IE 9]>
		<link rel="stylesheet" type="text/css" href="/css/ie9.css" />
	<![endif]-->
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" href="/css/ie8.css" />
	<![endif]-->
	<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="/css/ie7.css" />
	<![endif]-->
	
	<script src = "/js/jquery-1.10.2.js"></script>
	<script src = "/js/jquery-ui-1.10.4.custom.min.js"></script>
	<script src = "/js/main.js"></script>
	<style>
		.error {color: #FF0000;}
	</style>
	
</head>
<body>
	<?php include '../user.php'; ?>
	
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.html'; ?>
	
	<div id="main">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/side_menu.html.php'; ?>	
		<div id="content">
			
			<h1 class="title">Cadastro</h1>
			<?php 				
				if ($_SERVER["REQUEST_METHOD"] == "GET") {			
					isAnyUserLoggedIn() or die('<p class="info">Precisa fazer login para criar um novo usuário</p>');
					($_SESSION['username'] == "fabio") or die('<p class="info">O usuário não tem privilégio para criar um novo usuário</p>');			 
					
				} 
				elseif ($_SERVER["REQUEST_METHOD"] == "POST") {	
					
					//checking if the user was logged in successfully					
					isAnyUserLoggedIn() or die('<p class="info">'.$message.'</p>');														
				}
				echo $msg_campoObrigatorio;
			?>
			
			<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				Nome do Usuário: <input type="text" name="username" maxlength="16" value="<?php echo $username; ?>">
				<span class="error">* <?php echo $usernameErr;?></span><br>
				Senha: <input type="password" name="pwd1" maxlength="19">
				<span class="error">* <?php echo $pwd1Err;?></span><br>
				Confirma senha: <input type="password" name="pwd2" maxlength="19">
				<span class="error">* <?php echo $pwd2Err;?></span><br>
				<input type="hidden" name="user_action" value="signup">
				<input type="submit" value="Cadastrar">
			</form>

		<?php 
				if(!empty($message)) { 
					echo '<p class="info">'.$message.'</p>'; 
				} 
			?>		
		
		</div>
	</div>
	
	
</body>
</html>