<?php
	require '../../libraries/phpass/PasswordHash.php';
	header('Content-type: text/plain');
	$t_hasher = new PasswordHash(10, FALSE);
	$correct = 'r@p1d0nl1n3';
	$hash = $t_hasher->HashPassword($correct);
	echo $hash;
?>