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
	<?php include '../order.php'; ?>
	
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.html'; ?>
	
	<div id="main">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/side_menu.html.php'; ?>	
		<div id="content">
			
			<h1 class="title">Novo pedido</h1>
			<?php 
				if ($_SERVER["REQUEST_METHOD"] == "GET") {			
					!empty($username) or die('<p class="info">Log in to create a new order</p>');
				} 
				elseif ($_SERVER["REQUEST_METHOD"] == "POST") {	
					
					//checking if the user was logged in successfully					
					!empty($username) or die('<p class="info">'.$message.'</p>');															
				}
			
				if (!$thereIsOrder):				
			?>			
			
			<p><span class="error">* required field.</span></p>
			<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				Código do Pedido: <input type="text" name="order_code" value="<?php echo $orderCode;?>">
				<span class="error">* <?php echo $orderCodeErr;?></span><br>
				Data: <input type="date" name="status_date" value="<?php echo $statusDate;?>">
				<span class="error">* <?php echo $statusDateErr;?></span><br>
				Status do pedido: <textarea name="order_status"><?php echo $orderStatus;?></textarea>
				<span class="error">* <?php echo $orderStatusErr;?></span><br>
				<input type="hidden" name="order_action" value="insert">
				<input type="submit" value="Submit">
			</form>

		<?php 
			endif;
			if(!empty($message)) { 
				echo '<p class="info">'.$message.'</p>'; 
			} 
		?>			
		
		</div>
	</div>
	
	
</body>
</html>