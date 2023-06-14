<?php
namespace Aman5537jains\ReportBuilder\Inputs;
 
use Illuminate\Support\Facades\Log;

class TouchPointSelectInput extends ReportInputs
{ 

      function queryValue(){
           
          return  $this->value;
      }
       function scripts(){
         
        return [
          
           
          
        ];
      
       }

       function styles(){
         return [
             
            ];
       }
     
       function html(){
        // $html ="<span>{$this->config['title']} </span>:<input type='text' readonly class='datefilter_{$this->name}' name='{$this->name}' value='{$this->value}' />";
           $html ="<span>{$this->config['title']} </span>: <select name='{$this->name}' value='{$this->value}'><option>Touchpoint</option></select>";
           return  $html;
       } 

} 


?>