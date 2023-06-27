<?php 
 
namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

class JsPdFWrapperPhp{

    public $lines=[];
    function __construct()
    {
        
    }
    function data($params){
        return 'data.'.$params;
    }
    function now(){
        return 'new Date().toLocaleString()';
    }

    function __call($name,$args){
        
        $this->addLine($name,$args);
    }

    function addLine($name,$args){
        foreach($args as $k=>$arg){
            if(gettype($arg)=="string"){
                $args[$k]="'".$arg."'";
            }
        }
        $params= implode(",",$args);

        $this->lines[]="doc.$name($params)";
    }

    function render(){
        return join("\n",$this->lines);

    }
}