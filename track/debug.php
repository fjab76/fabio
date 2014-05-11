<html>
 <head>
  <title>Say My Name</title>
 </head>
 <body>
<?php
echo '<h1>Hello ' . $_POST['order_number'] . '!</h1>';
?>
  <pre>
<strong>DEBUG:</strong>
<?php
print_r($_POST);
?>
  </pre>
 </body>
</html>