<?php
namespace Aman5537jains\ReportBuilder\Formatters;
 
 

class BaseColumnNameFormatter  
{
    public $column;
    function __construct($column){
        if(empty($column['name'])){
            throw new \Exception("column name cannot be empty");
        }
        $this->column=$column;
    }
    function name(){
        
        return $this->column['name'];
    }
    function format(){
        if(!isset($this->column["title"]))
        $this->column["title"] = $this->column['name'];
    }


    function render(){
        $this->format();
        return $this->column["title"] ;
    }

}