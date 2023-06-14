<?php
namespace Aman5537jains\ReportBuilder\Inputs;
 
use Illuminate\Support\Facades\Log;

class TextInput extends ReportInputs
{

     
       function html(){
//  print_r($this->settings);
$type="text";
if(is_array($this->settings['type'])){
       $type= $this->settings['type']['value'];
}
           return  "  <span>{$this->config['title']} :</span><input type='{@$type}' name='{$this->name}' value='{$this->value}'   />";
       } 

} 


?>