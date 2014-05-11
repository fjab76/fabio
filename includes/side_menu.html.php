
<div id="side_menu">
	<div id="order_track">
		<form action="/track/index.php" method="post">
			<label for="order_number">Digite seu Código do Pedido</label>
			<input id="order_number" name="order_number" class="ui-autocomplete-input" autocomplete="off" />	
			<input type="submit" name="submit" value="Busque" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" aria-disabled="false" />
		</form>
	</div>
	
	<div id="order_actions">
		<a href="/order/insert" >insert</a><br/>
		<a href="/order/search" >search</a>
	</div>
	
	<div id="login">
		User:<?php echo "{$_SESSION['username']}" ?><br/>
		<a href="/user/signup" >sign up</a><br/>
		<a href="/user/login" >login</a>
		<a href="/user/pwd_change" >change password</a>
		<a href="/user/logout" >logout</a>
	</div>
</div>
	