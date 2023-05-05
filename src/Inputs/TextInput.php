<?php
namespace Aman5537jains\ReportBuilder\Inputs;
 
use Illuminate\Support\Facades\Log;

class TextInput extends ReportInputs
{

     
       function html(){
//  print_r($this->settings);
           return  "  <span>{$this->config['title']} :</span><input type='{$this->settings['type']['value']}' name='{$this->name}' value='{$this->value}'   />";
       } 

} 


?>