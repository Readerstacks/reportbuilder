<?php
namespace Aman5537jains\ReportBuilder\Layouts;

use Aman5537jains\ReportBuilder\Layouts\BaseLayout;

class NumberViewLayout extends BaseLayout
{
   
    function scripts(){
        
         
        return [
             
                
        ];
    }

    function styles(){
        return [
                 
            ];
    }

    function render(){
        if($this->reportBuilder->error==''){
            $table ="";
            if(count($this->reportBuilder->columns)==1 && count($this->reportBuilder->rows)==1){
                 $row=(array)$this->reportBuilder->rows[0]->render();
                 $keys=array_keys($row);

                 $table .= "
                 <style>.numberview{ display:flex; width:100%;} </style>
                 <div class='numberview'><div class='num_title'>".$keys[0] ." -  </div><div class='num_count'> KSH". $row[$keys[0]]."</div></div>";

            }
       
        

        
        return $table ;
        }
        else{
            return "<span style='color:red'>".$this->reportBuilder->error."</span>";
        }
    }
    

}