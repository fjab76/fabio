	<?php
	
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/db.php';
		include $_SERVER['DOCUMENT_ROOT'] . '/includes/common.php';
	
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
				
	
		// define variables and set to empty values
		$usernameErr = $orderCodeErr = $statusDateErr = $orderStatusErr = "";
		$username = $orderId = $orderCode = $statusDate = $orderStatus = "";
		$orderAction = $message = "";
		$thereIsOrder = $successValidation = false;
		$showForm = true;
		
		try{
		
			session_start();
			$username = $_SESSION['username'];			
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {				
				
				//insert new order
				if($_POST["order_action"]=="insert") {
	
					if (empty($_POST["order_code"])) {
					    $orderCodeErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $orderCode = test_input($_POST["order_code"]);
					  }
				
					if (empty($_POST["status_date"])) {
					    $statusDateErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($orderCode) && !empty($statusDate) && !empty($orderStatus);
					
					if($successValidation){
							
						//check if order code already exists
						if(existOrder($orderCode)) {
							$message = "Pedido $orderCode já existe";
						}		
						else {
							insertOrder($orderCode,$statusDate,$orderStatus);
							$result = getOrderStatusByOrderCode($orderCode);							
							$message = "Pedido $orderCode foi criado corretamente";							
						}
						$thereIsOrder = true;
					}	
				}
			
				//search an order
				elseif($_POST["order_action"]=="search") {
					
					if (empty($_POST["order_code"])) {
					    $orderCodeErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    	$orderCode = test_input($_POST["order_code"]);
					    
						    //check if order code already exists
							if(!existOrder($orderCode)) {
								$message = "Pedido $orderCode não existe";
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
					    $statusDateErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($statusDate) && !empty($orderStatus);
					
					$orderCode = $_POST["order_code"];
					$statusId = $_POST['status_id'];
					$orderAction = $_POST["order_action"];
					
					if($successValidation){
							
						//check if order code already exists
						
						$sql = "update order_status set status_date='$statusDate' , status_description='$orderStatus' where id=$statusId";
						$pdo->exec($sql);			
						$message = "Status foi atualizado corretamente";	
						
						$thereIsOrder = true;
						$showForm = false;
						$result = getOrderStatusByOrderCode($orderCode);												
					}	
				}
				elseif($_POST["order_action"]=="addStatus") {
					
					if (empty($_POST["status_date"])) {
					    $statusDateErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $statusDate = test_input($_POST["status_date"]);
					  }	
					  
					 if (empty($_POST["order_status"])) {
					    $orderStatusErr = $msg_campoObrigatorioLiteral;
					  } 
					  else {
					    $orderStatus = test_input($_POST["order_status"]);
					  }
				
					$successValidation = !empty($statusDate) && !empty($orderStatus);
					$orderCode = $_POST["order_code"];
					$orderAction = $_POST["order_action"];
					
					if($successValidation){
							
						//check if order code already exists
						
						$orderId = getOrderIdByOrderCode($orderCode);						
						insertOrderStatus($orderId,$statusDate,$orderStatus);	
						$message = "Status foi criado corretamente";	
						
						$thereIsOrder = true;
						$showForm = false;
						$result = getOrderStatusByOrderCode($orderCode);												
					}	
				}
			}
			elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($username)) {
		
				parse_str($_SERVER['QUERY_STRING'], $output);
				if(array_key_exists('order_action',$output)){
					$orderAction = $output["order_action"];
				}			
					
				//delete order status
				if($orderAction=="delete") {					
								
					//retrieve order code
					$statusId = $output['status_id'];								
					$orderCode = getOrderCodeByStatusId($statusId );					
				
													
					deleteOrderStatus($statusId);	
					$message = "Status foi excluido corretamente";
					
					$thereIsOrder = true;
					$result = getOrderStatusByOrderCode($orderCode);		
																										
				}
				elseif($orderAction=="deleteOrder") {					
					
					$orderCode = $output["order_code"];		
					deletetOrder($orderCode);
					$message = "Pedido $orderCode foi exluido corretamente";	
																										
				}
				elseif($orderAction=="edit"){
										
					$statusId = $output['status_id'];								
					$orderCode = getOrderCodeByStatusId($statusId);
							
					$result = getOrderStatusByStatusId($statusId);					
					$row = $result->fetch();
					
					$statusDate = $row['status_date'];
					$orderStatus = $row['status_description'];
										
				}	
				elseif($orderAction=="addStatus"){
																	
					$orderCode = $output["order_code"];												
				}
				elseif($orderAction=="search"){
																	
					$orderCode = $output["order_code"];	
					$orderCode = test_input($orderCode);
					    
				    //check if order code already exists
					if(!existOrder($orderCode)) {
						$message = "Pedido $orderCode não existe";
					}
					else {
						$thereIsOrder = true;						
						$result = getOrderStatusByOrderCode($orderCode);													
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