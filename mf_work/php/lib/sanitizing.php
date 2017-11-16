<?php
function hsc ($str)
{
	return htmlspecialchars($str, ENT_QUOTES);
}

//$subjectに含まれる改行コードを$replaceで置き換える関数
function str_rp ($replace, $subject)
{
	$subject = str_replace("\r\n", "\r", $subject);
	$subject = str_replace("\r", "\n", $subject);
	$subject = str_replace("\n", $replace, $subject);
	
	return $subject;
}