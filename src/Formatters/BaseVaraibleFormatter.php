<?php

namespace Aman5537jains\ReportBuilder\Formatters;

class BaseVaraibleFormatter
{
    public $varaibles;
    public $var;

    public function __construct($var, $variables)
    {
        $this->variables = $variables;
        $this->var = $var;
    }

    public function query()
    {
        if (isset($this->variables[$this->var])) {
            $val = $this->variables[$this->var]['value'];

            return $val;
        }

        return false;
    }
}
