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
return   "<div class='form-group'>
<label  >{$this->config['title']}</label>
<input type='{@$type}' class='form-control' placeholder='{$this->config['title']}' name='{$this->name}' value='{$this->value}'>
</div>";
              
       } 

} 


?>