<?php
namespace classes;

class Config extends \entities\Object {
    public function __construct($config)
    {
        $this->__set('db', $config['db']);
    }
}