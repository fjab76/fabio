	<?php
	
		function test_input($data) {
		   $data = trim($data);
		   $data = stripslashes($data);
		   $data = htmlspecialchars($data);
		   return $data;
		}
		
		function existOrder($orderCode){

			global $pdo;			
			
			$sql = "select id from `order` where order_code='$orderCode'";
			$result = $pdo->query($sql);
	      $row = $result->fetch();
	      
	      return ($row[0]>0);	
		}
	
		function getOrderCodeByStatusId($statusId) {
			
			global $pdo;
			
			$sql = "select ord.order_code
			from `order` ord join order_status status on ord.id=status.order_id where status.id=$statusId";
			$result = $pdo->query($sql);
			$row = $result->fetch();
			$orderCode = $row[0];	
			return $orderCode;			
		}
	
		function getOrderIdByOrderCode($orderCode) {
			
			global $pdo;
			
			$sql = "select ord.id from `order` ord where ord.order_code='$orderCode'";
			$result = $pdo->query($sql);
			$row = $result->fetch();
			return $row[0];				
		}
	
		function insertOrderStatus($orderId,$statusDate,$orderStatus) {
			
			global $pdo;
			
			$pdo->exec("insert into order_status (order_id,status_date,status_description) values ($orderId,'$statusDate','$orderStatus')");				
		}	
		
		function deletetOrder($orderCode) {
			
			global $pdo;

			$pdo->exec("delete status from `order` ord,order_status status where ord.id=status.order_id and ord.order_code='$orderCode'");
			$pdo->exec("delete from `order` where order_code='$orderCode'");
						
		}	
		
		function deleteOrderStatus($statusId) {
			
			global $pdo;
			
			$sql = "delete from order_status where id=$statusId";
			$pdo->exec($sql);
		}
	
		function insertOrder($orderCode,$statusDate,$orderStatus) {
			
			global $pdo;
			
			$pdo->exec("insert into `order` (order_code) values ('$orderCode')");
			$lastId = $pdo->lastInsertId();	
			$pdo->exec("insert into order_status (order_id,status_date,status_description) values ($lastId,'$statusDate','$orderStatus')");
		}
	
		function getOrderStatusByOrderCode($orderCode) {
			
			global $pdo;
			
			$sql = "select status.id,status.status_date,status.status_description 
			from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
			$result = $pdo->query($sql);
			return $result;
		}
	
		function getOrderStatusByStatusId($statusId) {
			
			global $pdo;
			
			$sql = "select status.status_date,status.status_description from order_status status where status.id=$statusId";
			$result = $pdo->query($sql);
			return $result;
		}
		
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
	
		// define variables and set to empty values
		$orderCodeErr = $statusDateErr = $orderStatusErr = "";
		$orderId = $orderCode = $statusDate = $orderStatus = "";
		$orderAction = $message = "";
		$thereIsOrder = $successValidation = false;
		
		try{
		
			if ($_SERVER["REQUEST_METHOD"] == "POST") {				
				
				//insert new order
				if($_POST["order_action"]=="insert") {
	
					if (empty($_POST["order_code"])) {
					    $orderCodeErr = "Order code is required";
					  } 
					  else {
					    $orderCode = test_input($_POST["order_code"]);
					  }
				
					if (empty($_POST["status_date"])) {
					    $statusDateErr = "Date is required";
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = "Order status is required";
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($orderCode) && !empty($statusDate) && !empty($orderStatus);
					
					if($successValidation){
							
						//check if order code already exists
						if(existOrder($orderCode)) {
							$message = "Pedido $orderCode ja existe";
						}		
						else {
							insertOrder($orderCode,$statusDate,$orderStatus);
							$message = "Pedido $orderCode foi insertado correctamente";							
						}
					}	
				}
			
				//search an order
				elseif($_POST["order_action"]=="search") {
					
					if (empty($_POST["order_code"])) {
					    $orderCodeErr = "Order code is required";
					  } 
					  else {
					    	$orderCode = test_input($_POST["order_code"]);
					    
						    //check if order code already exists
							if(!existOrder($orderCode)) {
								$message = "Pedido $orderCode no existe";
							}
							else {
								$thereIsOrder = true;						
								$result = getOrderStatusByOrderCode($orderCode);													
							}
					  }
				}
			
				//update order status
				elseif($_POST["order_action"]=="edit") {
					
					if (empty($_POST["status_date"])) {
					    $statusDateErr = "Date is required";
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = "Order status is required";
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($statusDate) && !empty($orderStatus);
					
					if($successValidation){
							
						//check if order code already exists
						$orderCode = $_POST["order_code"];
						$statusId = $_POST['status_id'];
						$sql = "update order_status set status_date='$statusDate' , status_description='$orderStatus' where id=$statusId";
						$pdo->exec($sql);			
						$message = "Status foi actualizado correctamente";	
						
						$thereIsOrder = true;
						$result = getOrderStatusByOrderCode($orderCode);												
					}	
				}
				elseif($_POST["order_action"]=="addStatus") {
					
					if (empty($_POST["status_date"])) {
					    $statusDateErr = "Date is required";
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = "Order status is required";
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($statusDate) && !empty($orderStatus);
					
					if($successValidation){
							
						//check if order code already exists
						$orderCode = $_POST["order_code"];
						$orderId = getOrderIdByOrderCode($orderCode);						
						insertOrderStatus($orderId,$statusDate,$orderStatus);	
						$message = "Status foi insertado correctamente";	
						
						$thereIsOrder = true;
						$result = getOrderStatusByOrderCode($orderCode);												
					}	
				}
			}
			elseif ($_SERVER["REQUEST_METHOD"] == "GET") {

				parse_str($_SERVER['QUERY_STRING'], $output);	
				$orderAction = $output["order_action"];			
					
				//delete order status
				if($orderAction=="delete") {					
								
					//retrieve order code
					$statusId = $output['status_id'];								
					$orderCode = getOrderCodeByStatusId($statusId );					
				
													
					deleteOrderStatus($statusId);	
					$message = "Status foi eliminado correctamente";
					
					$thereIsOrder = true;
					$result = getOrderStatusByOrderCode($orderCode);		
																										
				}
				elseif($orderAction=="deleteOrder") {					
					
					$orderCode = $output["order_code"];		
					deletetOrder($orderCode);	
																										
				}
				elseif($orderAction=="edit"){
										
					$statusId = $output['status_id'];								
					$orderCode = getOrderCodeByStatusId($statusId);
							
					$result = getOrderStatusByStatusId($statusId);					
					$row = $result->fetch();
					
					$statusDate = $row['status_date'];
					$statusDescription = $row['status_description'];
										
				}	
				elseif($orderAction=="addStatus"){
																	
					$orderCode = $output["order_code"];												
				}							
			}
		}
		catch (PDOException $e){
			$output = 'Unable to access to the database, ' . $e->getMessage();
			include $_SERVER['DOCUMENT_ROOT'] .'/includes/error.html.php';
			exit();
		}
		
		
		
	?>