<!DOCTYPE html>
<html>
<head>
	<title>IMAP testing</title>
	<meta charset="utf-8">
	<style>
		body {
			background: #f0f0f0;
			font-family: Helvetica, Arial, sans-serif;
			padding: 2em;
		}
		.msg {
			background: #fff;
			padding: 2em;
			box-shadow: 0px 5px 10px #d0d0d0;
			margin: 1em 2em;
			line-height: 1.4em;
		}
		.meta {
			color: #b0b0b0;
			font-size: 0.8em;
			line-height: 1em;
		}
	</style>
</head>
<body>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

$hostname = '{mail.sis.uta.fi:993/imap/ssl}Inbox'; 
$username = ''; $password = ''; 
$mailbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail.sis.uta.fi: ' . imap_last_error());


if ($mailbox == false) {
	echo "<p>Error: Can't open mailbox!</p>";
	echo imap_last_error();
}
else {

	//Check number of messages
	$num = imap_num_msg($mailbox);

	//if there is a message in your inbox
	if( $num > 0 ) { //this just reads the most recent email. In order to go through all the emails, you'll have to loop through the number of messages

		echo '<h1>INBOX ('.$num.')</h1><hr>';
		for($n = 1; $n <= $num; $n++) {
			$email = imap_fetchheader($mailbox, $n); //get email header

			$lines = explode("\n", $email);

			$from = "";
			$subject = "";
			$to = "";
			$splittingheaders = true;

			for ($i=0; $i < count($lines); $i++) {
				if ($splittingheaders) {
					//$headers .= $lines[$i]."\n";

					if (preg_match("/^Subject: (.*)/", $lines[$i], $matches)) {
						$subject = $matches[1];
					}
					if (preg_match("/^From: (.*)/", $lines[$i], $matches)) {
						$from = $matches[1];
					}
					if (preg_match("/^To: (.*)/", $lines[$i], $matches)) {
						$to = $matches[1];
					}

				}
			}

			echo '<div class="msg">';
			echo '<div class="meta"><strong>FROM:</strong> '.$from.'<br>';
			echo '<strong>TO:</strong> '.$to;
			echo '</div>';
			echo "<h3>".imap_utf8($subject)."</h3>";
			echo str_replace("\n", "<br>", trim(imap_qprint(imap_body($mailbox, $n))));
			echo '</div>';
		}
		//delete message
		//imap_delete($mailbox,$num);
		//imap_expunge($mailbox);
	}
	else {
		echo "No messages found";
	}

	imap_close($mailbox);
}

?>
</body>
</html>