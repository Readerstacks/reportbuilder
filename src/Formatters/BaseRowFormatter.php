<?php
namespace Aman5537jains\ReportBuilder\Formatters;
 
 

class BaseRowFormatter  
{
    public $row;
    function __construct($row){
        if(empty($row)){
            throw new \Exception("row cannot be empty");
        }
        $this->row=$row;
    }
    function getColumnValue($name){
         
        return $this->row->{$name};

    }

    function format($name=''){
     
        // if($this->row->image){
        //     $this->row->image =" Image Here";
        // }
       
    }


    function render($name=''){
        $this->format($name);
        if($name!=''){
             return $this->row->{$name} ;
        }
        else{
            foreach($this->row as $name=>$value)
                 $this->format($name);
            return   $this->row;
        }
    }

}