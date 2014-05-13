

<div id="side_menu">
	<div id="order_track">
		<form action="/track/index.php" method="post">
			<label for="order_number">Digite seu Código do Pedido</label>
			<input id="order_number" name="order_number" class="ui-autocomplete-input" autocomplete="off" />	
			<input type="submit" name="submit" value="Busque" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" aria-disabled="false" />
		</form>
	</div>
	
	<div id="login">
		<?php if(empty($username)): ?>
		<span style="font-size:100%">Area privada para funcionarios<br/></span>
		<a href="/user/signup" ><button type="button" ><span style="font-size:70%">signup</span></button></a>
		<a href="/user/login" ><button type="button" ><span style="font-size:70%">login</span></button></a>
		<?php else: ?>
		<span style="font-size:110%">User:<?php echo " $username" ?><br/></span> 		
		<a href="/user/pwd_change" ><button type="button" ><span style="font-size:70%">Change pwd</span></button></a>
		<a href="/user/logout" ><button type="button" ><span style="font-size:70%">logout</span></button></a>
		<?php endif; ?>
	</div>
	
	<?php if(!empty($username)): ?>
	<div id="order_actions">
		<span style="font-size:110%">Rastreamento de pedidos<br/></span>
		<a href="/order/insert" ><button type="button" ><span style="font-size:70%">insert</span></button></a>
		<a href="/order/search" ><button type="button" ><span style="font-size:70%">search</span></button></a>
	</div>
	<?php endif; ?>
	
</div>
	