<?php
$loader = require '../vendor/autoload.php';

print "Enter Hostname: ";
$hostname = stream_get_line(STDIN, 1024, PHP_EOL);
print 'Enter USER: ';
$username = stream_get_line(STDIN, 1024, PHP_EOL);
print 'Enter PASS: ';
$password = stream_get_line(STDIN, 1024, PHP_EOL);

$me = new Ole1986\MailReceiver("{".$hostname.":143/novalidate-cert}INBOX", $username, $password);

$result = $me->Unread()->Subject("Mail queue")->FetchAll();

print_r($result);