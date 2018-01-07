# MailReceiver
<sup>Version: 1.0.3 | License: MIT | Author: ole1986</sup>

This class is used to receive and filter emails from either an IMAP or POP3 account using the PHP extension "php_imap"

## Example

```
new Ole1986\MailReceiver("{hostname:143}INBOX", "username", "password");
// add '/novalidate-cert' to the hostname to skip certificate validation

// fetch unread emails containing subject "Mail queue"
$me->Unread()->Subject("Mail queue")->FetchAll();
```