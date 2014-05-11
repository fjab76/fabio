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
			
			<h1 class="title">Actualiza pedido</h1>
			<p><span class="error">* required field.</span></p>
			<form  method="post" action="/order/search/"/>
				Código do Pedido: <?php echo $orderCode; ?> <br>
				<span class="error">* <?php echo $orderCodeErr;?></span><br>
				Data: <input type="date" name="status_date" value="<?php echo $statusDate; ?>"/>
				<span class="error">* <?php echo $statusDateErr;?></span><br>
				Status do pedido: <textarea name="order_status"><?php echo $statusDescription; ?></textarea>
				<span class="error">* <?php echo $orderStatusErr;?></span><br>				
				<input type="hidden" name="order_action" value="<?php echo $orderAction; ?>">
				<input type="hidden" name="order_code" value="<?php echo $orderCode; ?>">
				<input type="hidden" name="status_id" value="<?php echo $statusId; ?>">
				<input type="submit" value="Submit">
				<input type="button" value="Cancel" onclick="window.location='/order/search/'" />
			</form>

	<?php
	echo "<h2>Your Input:</h2>";

		
	echo "$message";
		
	echo "<br>";
	echo $orderCode;
	echo "<br>";
	echo $statusDate;
	echo "<br>";
	echo $orderStatus;
	?>	
		</div>
	</div>
	
	
</body>
</html>