<?php

	$contrasenia = 'tantsevat';
	
	$hash = password_hash ($contrasenia, PASSWORD_DEFAULT);

	if(password_verify($contrasenia,$hash))
	{
		echo 'contraseña: '.$contrasenia.'\n $hash: '.$hash.' \n';
	}else{
		echo 'contraseña generada incorrectamente';
	}
?>
