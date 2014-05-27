<?php 

//include $_SERVER['DOCUMENT_ROOT'] . '/includes/common.php';

 ?>


<div id="side_menu">
	<div id="order_track">
		<form action="/track/index.php" method="post">
			<label for="order_number">Digite seu Código do Pedido</label>
			<input id="order_number" name="order_number" class="ui-autocomplete-input" autocomplete="off" />	
			<input type="submit" name="submit" value="Busque" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" aria-disabled="false" />
		</form>
	</div>
	
	<div id="login">
		<?php if(!isset($_SESSION) || !array_key_exists('username',$_SESSION)): ?>
		<span style="font-size:100%">Acesso Área Privada para funcionarios<br/></span>		
		<a href="/user/login" ><button type="button" ><span style="font-size:70%">Login</span></button></a>
		<?php else: ?>
		<span style="font-size:110%">Usuário:<?php echo " {$_SESSION['username']}" ?><br/></span> 		
		<a href="/user/pwd_change" ><button type="button" ><span style="font-size:70%">Alterar senha</span></button></a>
		<a href="/user/logout" ><button type="button" ><span style="font-size:70%">Logout</span></button></a>
		<?php if($_SESSION['username'] == "fabio"): ?>
		<a href="/user/signup" ><button type="button" ><span style="font-size:70%">Criar novo Usuário</span></button></a>
		<?php endif; endif; ?>
	</div>
	
	<?php if(isset($_SESSION) && array_key_exists('username',$_SESSION)): ?>
	<div id="order_actions">
		<span style="font-size:110%">Gestão de pedidos<br/></span>
		<a href="/order/insert" ><button type="button" ><span style="font-size:70%">Criar</span></button></a>
		<a href="/order/search" ><button type="button" ><span style="font-size:70%">Pesquisar</span></button></a>
	</div>
	<?php endif; ?>
	
</div>
	