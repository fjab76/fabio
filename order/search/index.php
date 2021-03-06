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
			
			
			<h1 class="title">Pesquisar pedido</h1>
			<?php 
				!empty($username) or die('<p class="info">Precisa fazer login para pesquisar um pedido</p>'); 
				if (!$thereIsOrder){
					echo $msg_campoObrigatorio;
			?>

			<form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				Código do Pedido: <input type="text" name="order_code">
				<span class="error">* <?php echo $orderCodeErr;?></span><br>				
				<input type="hidden" name="order_action" value="search">
				<input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
				<input type="submit" value="Pesquisar">				
			</form>
				
			
			
			<?php }else{ ?>
			Código do Pedido: "<?php echo $orderCode; ?>"<br/>
			<p><a href="/order/search/?order_code=<?php echo $orderCode ?>&order_action=deleteOrder" >Excluir pedido</a></p>
			<table id="order_table">
			<thead class="ui-widget-header">
				<tr>
					<th></th>
					<th></th>
					<th>Data</th>
					<th>Status do pedido</th>
				</tr>
			</thead>
			<tbody class="ui-widget-content">

			<?php	while ($row = $result->fetch()): ?>
		
				<tr>
		    	  <td> <a href="/order/update/?status_id=<?php echo $row['id'] ?>&order_action=edit" >Editar</a> </td>
		    	  <td> <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?status_id=<?php echo $row['id'] ?>&order_action=delete" >Excluir</a> </td>
		        <td> <?php echo $row['status_date'] ?> </td>
		        <td> <?php echo $row['status_description'] ?> </td>		    
		    	</tr>

			<?php endwhile; ?>
			</tbody>
			</table>
			<p><a href="/order/update/?order_code=<?php echo $orderCode ?>&order_action=addStatus" >Criar status</a></p>			
			
			<?php 
				}
				
				if(!empty($message)) { 
					echo '<p class="info">'.$message.'</p>'; 
				} 
			?>

		</div>
	</div>
	
	
</body>
</html>