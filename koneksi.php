<?php

try {
	$pdo = new PDO('mysql:host=localhost;', 'root', '');
} catch(PDOException $e) {
	echo 'Connection failed'.$e->getMessage();
}

?>