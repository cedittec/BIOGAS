<?php
	session_start();
	unset($_SESSION['userbio']);
	unset($_SESSION['typebio']);
	unset($_SESSION['idbio']);
	header('Location: login.php');
?>