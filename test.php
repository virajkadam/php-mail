<?php

$to             = 'kadam.virajkadam@gmail.com';
$subject        = 'ATTCH TEST'. mt_rand();
$messagehtml    = '<b>HI IAM VIRAJ WITH ATTACHMENT</b>';
$from           = 'kadam.virajkadam@gmail.com';
$fileatt        = 'test.pdf';
$replyto        = 'kadam.virajkadam@gmail.com';

echo mail_file($to, $subject, $messagehtml, $from, $fileatt, $replyto);

function mail_file( $to, $subject, $messagehtml, $from, $fileatt, $replyto="" ) {
	// handles mime type for better receiving
	$eol = PHP_EOL;
	$ext = strrchr( $fileatt , '.');
	$ftype = "";
	if ($ext == ".doc") $ftype = "application/msword";
	if ($ext == ".jpg") $ftype = "image/jpeg";
	if ($ext == ".gif") $ftype = "image/gif";
	if ($ext == ".zip") $ftype = "application/zip";
	if ($ext == ".pdf") $ftype = "application/pdf";
	if ($ftype=="") $ftype = "application/octet-stream";
	 
	// read file into $data var
	$file = fopen($fileatt, "rb");
	$data = fread($file,  filesize( $fileatt ) );
	fclose($file);
 
	// split the file into chunks for attaching
	$content = chunk_split(base64_encode($data));
	$uid = md5(uniqid(time()));
 
	// build the headers for attachment and html
	$h = "From: ".$from." $eol";
	if ($replyto) $h .= "Reply-To: $replyto $eol";
	$h .= "MIME-Version: 1.0 $eol";
	$h .= "Content-Type: multipart/mixed; boundary=\"$uid\" $eol";
	$h .= "This is a multi-part message in MIME format. $eol";
	$h .= "--$uid $eol";
	$h .= "Content-type:text/html; charset=iso-8859-1 $eol";
	$h .= "Content-Transfer-Encoding: 7bit $eol";
	$h .= $messagehtml." $eol";
	$h .= "--$uid $eol";
	$h .= "Content-Type: $ftype; name=\"basename($fileatt)\" $eol";
	$h .= "Content-Transfer-Encoding: base64 $eol";
	$h .= "Content-Disposition: attachment; filename=\"basename($fileatt)\" $eol";
	$h .= $content." $eol";
	$h .= "--$uid--";
 
	// send mail
	return mail( $to, $subject, strip_tags($messagehtml), $h ) ;
}

