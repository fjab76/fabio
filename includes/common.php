<?php

$msg_campoObrigatorio = "<p><span class='error'>* campo obrigatorio.</span></p>";

function test_input($data) {
	   $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}