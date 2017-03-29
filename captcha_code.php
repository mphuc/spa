<?php
	session_start();

	$ranStr = md5(microtime());
	
	$ranStr = hexdec( crc32($ranStr));

	$ranStr = substr($ranStr, 0, 6);

	$_SESSION['cap_code'] = $ranStr;
	//echo $_SESSION['cap_code'];die;
	$newImage = imagecreatefromjpeg("images/bg3.jpg");

	$txtColor = imagecolorallocate($newImage, 255, 255, 255);

	imagestring($newImage, 5, 3, 8, $ranStr, $txtColor);

	header("Content-type: image/jpeg");

	imagejpeg($newImage);