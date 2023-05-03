<?php
namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

use Aman5537jains\ReportBuilder\Layouts\BaseLayout;

class TableLayout extends BaseLayout
{
   
    function scripts(){
        
        $script = <<<SCRIPT
               
             new DataTable('.tbl_report');
        SCRIPT;

        return [
            'datatable'=>[
                "src"=>'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js'
            ],
            'script'=>[
                'text'=>$script
                ]
        ];
    }

    function styles(){
        return [
            'datatable'=>[
                "src"=>"https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
            ]
            ];
    }

    function render(){
        if($this->reportBuilder->error==''){
        $table=' ';

     
        // foreach($this->reportBuilder->report->variables  as $name=>$var){
        //     $table.=$var['obj']->render();

        // }
        $table .=  ' 
        <style>
       
        </style>
        
        <table id="tbl_report" class="tbl_report table">';
        $table .=  '<thead><tr>';
  
        foreach($this->reportBuilder->columns as $column){

            $table.=   '<th>'.$column->render().' </th>';
        }
        $table .=  '</tr><thead><tbody>';
        foreach($this->reportBuilder->rows as $row){
            $table.=   '<tr>';
            foreach($this->reportBuilder->columns as $column){

                $table.=   '<td>'.$row->render($column->name()).' </td>';
            }
            $table.=   '</tr>';
        }
        
        $table.= "</tbody></table>";
        

        
        return $table ;
        }
        else{
            return "<span style='color:red'>".$this->reportBuilder->error."</span>";
        }
    }
    

}