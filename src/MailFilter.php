<?php
namespace Ole1986;

class MailFilter {
    private $conditions;

    public function Contains($property, $value) {
        if(!in_array($property, ['subject', 'from', 'to', 'date', 'body'])) throw new Exception('Contains not supported for' . $property);
        $this->$property = ['matchContains', $value];
    }

    public function Equals($property, $value) {
        $this->$property = ['matchEquals', $value];
    }

    public function Greater($property, $value) {
        if(!in_array($property, ['udate'])) throw new Exception('Greater not supported for' . $property);
        $this->$property = ['matchGreater', $value];
    }

    public function Lower($property, $value) {
        if(!in_array($property, ['udate'])) throw new Exception('Lower not supported for' . $property);
        $this->$property = ['matchLower', $value];
    }

    public function Match($item){
        foreach(get_object_vars($this) as $k => $v) {
            if(!isset($item->$k)) continue;

            list($method, $value) = $v;

            if(!$this->$method($value, $item->$k)) return false;
        }
        return true;
    }

    public function __call($name, $arg){
        list($local, $remote) = $arg;

        switch($name){
            case 'matchContains':
                if(stripos($remote, $local) === false) return false;
                break;
            case 'matchEquals':
                if($remote != $local) return false;
                break;
            case 'matchLower':
                if($remote > $local) return false;
                break;
            case 'matchGreater':
                if($remote < $local) return false;
                break;
        }
        return true;
    }
}