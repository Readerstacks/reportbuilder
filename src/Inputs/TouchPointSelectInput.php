<?php

namespace Aman5537jains\ReportBuilder\Inputs;

class TouchPointSelectInput extends ReportInputs
{
    public function queryValue()
    {
        return  $this->value;
    }

     public function scripts()
     {
         return [

         ];
     }

     public function styles()
     {
         return [

         ];
     }

     public function html()
     {
         // $html ="<span>{$this->config['title']} </span>:<input type='text' readonly class='datefilter_{$this->name}' name='{$this->name}' value='{$this->value}' />";
         $html = "<span>{$this->config['title']} </span>: <select name='{$this->name}' value='{$this->value}'><option>Touchpoint</option></select>";

         return  $html;
     }
}
