<?php
	session_start();
    session_regenerate_id(true);

	if(session_destroy()) // Destroying All Sessions
	{
		die(header("Location: index.php")); // Redirecting To Home Page
	}
?>