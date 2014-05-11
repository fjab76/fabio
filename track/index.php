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

	
</head>
<body>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php'; ?>
	
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.html'; ?>
	
	<div id="main">
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/side_menu.html.php'; ?>	
		<div id="content">
		
			<div id="order_list">
			
		<?php
			$orderCode = $_POST['order_number'];

			try
			{
				$sql = "select count(*) 
				from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
	         $result = $pdo->query($sql);
	         $row = $result->fetch();
	         
	         if($row[0]<=0){
					echo "<div class='info left'>Desculpas, não há dados para o seu pedido</div>";	
				}			
				else{
					$sql = "select ord.order_code,status.status_date,status.status_description 
					from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
		         $result = $pdo->query($sql);
	         }
			}
			catch (PDOException $e)
			{
				$output = 'Unable to connect to the database server, ' . $e->getMessage();
				include $_SERVER['DOCUMENT_ROOT'] . '/includes/error.html.php';
				exit();
			}
			
			if($row[0]>0):
							
		?>

			<table id="order_table">
			<thead class="ui-widget-header">
				<tr>
					<th>Código do Pedido</th>
					<th>Data</th>
					<th>Status do pedido</th>
				</tr>
			</thead>
			<tbody class="ui-widget-content">

		<?php while ($row = $result->fetch()): ?>
				 <tr>
			    	  <td> <?php echo $row['order_code'] ?> </td>
			        <td> <?php echo $row['status_date'] ?> </td>
			        <td> <?php echo $row['status_description'] ?> </td>
			    
			    </tr>

		<?php endwhile ?>
			</tbody>
			</table>

		<?php endif; ?>
			
		
		
		</div>
		</div>
	</div>
	
</body>
</html>