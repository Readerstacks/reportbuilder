<?php
namespace Aman5537jains\ReportBuilder\Formatters;
 
 

class BaseColumValueFormatter  
{
    public $column;
    public $row;
    function __construct($column,$row){
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