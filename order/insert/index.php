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
			<?php !empty($username) or die("Please, log in to access to this functionality") ?>
			<p><span class="error">* required field.</span></p>
			<form  method="post" action="/order/search/">
				Código do Pedido: <input type="text" name="order_code">
				<span class="error">* <?php echo $orderCodeErr;?></span><br>
				Data: <input type="date" name="status_date">
				<span class="error">* <?php echo $statusDateErr;?></span><br>
				Status do pedido: <textarea name="order_status"></textarea>
				<span class="error">* <?php echo $orderStatusErr;?></span><br>
				<input type="hidden" name="order_action" value="insert">
				<input type="submit" value="Submit">
			</form>

	<?php
		
	echo "$message";
		
	?>	
		</div>
	</div>
	
	
</body>
</html>