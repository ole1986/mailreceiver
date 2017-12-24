<?php
namespace Ole1986;
/**
 * MailReceiver to fetch email from an IMAP or POP3 account by filtering the result
 * 
 * Author: ole1986
 */
class MailReceiver {

    private $limit;
    private $mbox;
    private $list;
    private $filter;

    public function __construct($host, $user, $pass){
        $this->mbox = imap_open($host, $user, $pass);

        $this->filter = new MailFilter();
        $this->limit = 50;
    }

    public function __destruct()
	{
        imap_close($this->mbox);
	}

    private function load() {
        $MC = imap_check($this->mbox);
        $top = ($MC->Nmsgs > $this->limit) ? ($MC->Nmsgs - $this->limit).":{$MC->Nmsgs}" : "1:{$MC->Nmsgs}";

        $this->list = imap_fetch_overview($this->mbox, $top, 0);

        $this->list = array_filter($this->list, function($item){
            return $this->filter->match($item);
        });
    }

    private function Limit($num) {
        $this->limit = $num;
        return $limit;
    }

    /**
     * Fetch all (filtered) mails as array
     * @param {bool} $peek either mark them as reed or keep them as unread
     */
    public function FetchAll($peek = false){
        $this->load();

        $options = 0;
        if($peek) $options = FT_PEEK;

        foreach($this->list as $item) {
            $item->body = imap_body($this->mbox, $item->msgno, $options);
        }

        // after body received apply the body filter
        if(isset($this->filter->body)) {
            $this->list = array_filter($this->list, function($item){ return stripos($item->body, $this->filter->body[1]) !== false;  });
        }

        return $this->list;
    }

    /**
     * Filter only on unread emails
     */
    public function Unread() {
        $this->filter->Equals('seen', 0);
        return $this;
    }

    public function DateGreater($time) {
        $this->filter->Greater('udate', $time);
    }
    public function DateLower($time) {
        $this->filter->Lower('udate', $time);
    }

    /**
     * filter only subject contains (case-insensitive)
     */
    public function Subject($title) {
        $this->filter->Contains('subject', $title);
        return $this;
    }

    public function Body($body) {
        $this->filter->Contains('body', $body);
        return $this;
    }
}