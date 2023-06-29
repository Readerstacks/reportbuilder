<?php 
 
namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

use PhpParser\Node\Expr\Cast\Object_;

class JsPdFWrapperPhp{

    public $lines=[];
    function __construct()
    {
        
    }
    function data($params){
        return 'data.'.$params;
    }
    function js($str){
        return  $this->lines[]=$str ;
    }
    function jsCode($str){
        return  new JsCode($str) ;
    }
    function now(){
        return 'new Date().toLocaleString()';
    }
    function __get($name){
        
      
        return (new ObjectChain())->add($name);
    }

    function __call($name,$args){
        
        $this->addLine($name,$args);
    }

    function addLine($name,$args){
        foreach($args as $k=>$arg){
            if(gettype($arg)=="string"){
                $args[$k]="'".$arg."'";
            }
          
            if(gettype($arg)=="object" && $arg instanceof JsCode){
                $args[$k]=$arg->render();
            }
        }
        $params= implode(",",$args);

        $this->lines[]="doc.$name($params)";
    }

    function render(){
        return join("\n",$this->lines);

    }
}


class JsCode{
    public $code;
    function __construct($code)
    {
        $this->code=$code;
    } 
    function render(){
        return $this->code;
    }   
}

class ObjectChain{
    public $chain;
    
    function add($name,$args=[]){
       
        $this->chain[]=$name;
        return $this;
    }
    function __get($name){
      
        return $this->add($name);
    }

    function __call($name,$args){
        
            foreach($args as $k=>$arg){
                if(gettype($arg)=="string"){
                    $args[$k]="'".$arg."'";
                }
              
                if(gettype($arg)=="object" && $arg instanceof JsCode){
                    $args[$k]=$arg->render();
                }
            }
            $params= implode(",",$args);
    
            
           
            $this->chain[]= $name."($params)";
       
        
        return $this;
    }

    function render(){
        return join(".",$this->chain);
    }   

    function __toString()
    {
        return join(".",$this->chain);
    }
}