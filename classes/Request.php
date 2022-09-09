<?php
namespace classes;

class Request extends \entities\Object {
    public function __construct($request)
    {
        if(is_array($request)) {
            foreach ($request as $name => $value) {
                $this->__set($name, $value);
            }
        }
    }

    public function isAjax()
    {
        return isset($this->data['isAjax']) ?: false;
    }
}