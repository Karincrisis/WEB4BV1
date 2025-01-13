<?php

	$contrasenia = 'admin1000';
	
	$hash = password_hash ($contrasenia, PASSWORD_DEFAULT);

	if(password_verify($contrasenia,$hash))
	{
		echo 'contraseña: '.$contrasenia.'\n $hash: '.$hash.' \n';
	}else{
		echo 'contraseña generada incorrectamente';
	}



	/* admin -> admin1000 
	 * candidato -> candidato
	 * empleador -> empleador
	 *
	 * */
?>
