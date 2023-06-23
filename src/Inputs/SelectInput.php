<?php
namespace Aman5537jains\ReportBuilder\Inputs;

use Aman5537jains\ReportBuilder\Inputs\ReportInputs;
use Illuminate\Support\Facades\Log;

class SelectInput extends ReportInputs
{ 

      function queryValue(){
           
          return  $this->value;
      }
       function scripts(){
         
        return [
            'select2'=>[
                'src'=>'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'script'=>[
                'text'=>"  
                    $('.select2').select2( );
                "]
           
          
        ];
      
       }

       public static function filter($input,$model){
        return $model;
       }

       function styles(){
         return [
             
            ];
       }
     
       function html(){
        // dump( );
        $model = new $this->settings['model'];
        $colid=$this->settings['columnid'];
        $columnvalue=$this->settings['columnvalue'];
        $filterClass = $this->settings['filterClass'];
        $filterMethod = $this->settings['filterMethod'];
        if($filterClass!=''){
             
             $model = $filterClass::$filterMethod($this,$model);
        }
        $data = $model->get();
        $options='';
        foreach($data as $record){
            $selected=$this->value==$record->$colid?"selected":"";
            $options.=" <option  $selected  value='{$record->$colid}'>{$record->$columnvalue}</option>";
        }

        // $html ="<span>{$this->config['title']} </span>:<input type='text' readonly class='datefilter_{$this->name}' name='{$this->name}' value='{$this->value}' />";
           $html ="<div class='form-group'>
           <label  >{$this->config['title']}</label> <select class='select2 form-control' name='{$this->name}' value='{$this->value}'>
           <option value=''>Select {$this->config['title']} </option>
           $options
           
           </select>";
           return  $html;
       } 

} 


?>