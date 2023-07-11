<?php

namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

class JsPdFWrapperPhp
{
    public $lines = [];

    public function __construct()
    {
    }

    public function data($params)
    {
        return 'data.'.$params;
    }

    public function js($str)
    {
        return  $this->lines[] = $str;
    }

    public function jsCode($str)
    {
        return  new JsCode($str);
    }

    public function now()
    {
        return 'new Date().toLocaleString()';
    }

    public function __get($name)
    {
        return (new ObjectChain())->add($name);
    }

    public function __call($name, $args)
    {
        $this->addLine($name, $args);
    }

    public function addLine($name, $args)
    {
        foreach ($args as $k=>$arg) {
            if (gettype($arg) == 'string') {
                $args[$k] = "'".$arg."'";
            }

            if (gettype($arg) == 'object' && $arg instanceof JsCode) {
                $args[$k] = $arg->render();
            }
        }
        $params = implode(',', $args);

        $this->lines[] = "doc.$name($params)";
    }

    public function render()
    {
        return join("\n", $this->lines);
    }
}

class JsCode
{
    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function render()
    {
        return $this->code;
    }
}

class ObjectChain
{
    public $chain;

    public function add($name, $args = [])
    {
        $this->chain[] = $name;

        return $this;
    }

    public function __get($name)
    {
        return $this->add($name);
    }

    public function __call($name, $args)
    {
        foreach ($args as $k=>$arg) {
            if (gettype($arg) == 'string') {
                $args[$k] = "'".$arg."'";
            }

            if (gettype($arg) == 'object' && $arg instanceof JsCode) {
                $args[$k] = $arg->render();
            }
        }
        $params = implode(',', $args);

        $this->chain[] = $name."($params)";

        return $this;
    }

    public function render()
    {
        return join('.', $this->chain);
    }

    public function __toString()
    {
        return join('.', $this->chain);
    }
}
