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
	      
	      return ($row[0]);	
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
						if(existOrder($orderCode)>0) {
							$message = "Pedido $orderCode ja existe";
						}		
						else {
							$pdo->exec("insert into `order` (order_code) values ('$orderCode')");
							$lastId = $pdo->lastInsertId();	
							$pdo->exec("insert into order_status (order_id,status_date,status_description) values ($lastId,'$statusDate','$orderStatus')");
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
						    $orderId = existOrder($orderCode);
							if($orderId<1) {
								$message = "Pedido $orderCode no existe";
							}
							else {
								$thereIsOrder = true;						
								$sql = "select status.id,status.status_date,status.status_description 
								from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
								$result = $pdo->query($sql);													
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
						$sql = "select status.id,status.status_date,status.status_description 
						from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
						$result = $pdo->query($sql);												
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
						$sql = "select status.id,status.status_date,status.status_description 
						from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
						$result = $pdo->query($sql);												
					}	
				}
			}
			elseif ($_SERVER["REQUEST_METHOD"] == "GET") {

				parse_str($_SERVER['QUERY_STRING'], $output);	
				$orderAction = $output["order_action"];			
					
				//delete order status
				if($orderAction=="delete") {
					
					/*if($_POST["submit"]=="delete_order"){						
						$orderId = $_POST['order_id'];
						$sql = "delete * from order_status where order_id=$orderId";
						$pdo->exec($sql);
						
						$sql = "delete * from `order` where id=$orderId";
						$pdo->exec($sql);							
								
						$message = "Pedido $orderCode foi eliminado correctamente";											
					}*/
					//elseif($_POST["submit"]=="delete_status"){
						
						//$statusToBeDeleted = $_POST["statusList"];
						//if(!empty($statusToBeDeleted)) {
							//$num = count($statusToBeDeleted);
							//for($i=0; $i<$num; $i++) {	
								
								//retrieve order code
								$statusId = $output['status_id'];								
								$orderCode = getOrderCodeByStatusId($statusId );					
							
																
								$sql = "delete from order_status where id=$statusId";
								$pdo->exec($sql);		
								$message = "Status foi eliminado correctamente";
								
								$thereIsOrder = true;
								$sql = "select status.id,status.status_date,status.status_description 
								from `order` ord join order_status status on ord.id=status.order_id where ord.order_code='$orderCode'";
								$result = $pdo->query($sql);													
							//}
						//}
					//}
				}
				elseif($orderAction=="edit"){
										
					$statusId = $output['status_id'];								
					$orderCode = getOrderCodeByStatusId($statusId);
							
					$sql = "select status.status_date,status.status_description 
					from order_status status where status.id=$statusId";
					$result = $pdo->query($sql);					
					$row = $result->fetch();
					
					$statusDate = $row['status_date'];
					$statusDescription = $row['status_description'];
										
					//updating statuses
					/*$numStatuses = $_POST["numStatuses"];
					for($i=0; $i<$numStatuses; $i++) {							
						$statusDate = 	"status_date".$i;
						$statusDescription = "status_description".$i;
						$statusId = "status_id".$i;
						$sql = "update order_status set status_date=$_POST[$statusDate] , status_description=$_POST[$statusDescription] where id=$_POST[$statusId]";
						$pdo->exec($sql);		
						$message = "Statuses foi actualizado correctamente";											
					}*/
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