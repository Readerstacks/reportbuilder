<?php
namespace Aman5537jains\ReportBuilder\Layouts;

use Aman5537jains\ReportBuilder\Layouts\BaseLayout;

use function GuzzleHttp\json_encode;

class CanvasChartLayout extends BaseLayout
{

    function settingBuilder(){
        return [
          "html"=>"",
          "script"=>""
        ];
    }
    function scripts(){
        
        //  dd($this->layoutSettings);
        // {$this->layoutSettings['type']}
        // $this->reportBuilder->columns
        $labels=[];
        $data_column=[];
        $colors_column=[];
        
        // foreach($this->reportBuilder->rows as $row){
           
        //     if(isset($row->row->{$this->layoutSettings['label_column']})){
        //       $labels[]=$row->row->{$this->layoutSettings['label_column']};
        //     }
        //     if(isset($row->row->{$this->layoutSettings['data_column']})){
        //       $data_column[]=$row->row->{$this->layoutSettings['data_column']};
        //     }
        //     if(isset($row->row->{$this->layoutSettings['colors_column']})){
        //       $colors_column[]=$row->row->{$this->layoutSettings['colors_column']};
        //     }
        // }
        // $labels=json_encode( $labels);
        // $data_column=json_encode( $data_column);
        // $colors_column=json_encode( $colors_column);
        $script=<<<script
                    
                  
        var chart = new CanvasJS.Chart("chartContainer", {
            title:{
                text: "My First Chart in CanvasJS"              
            },
            data: [              
            {
                // Change type to "doughnut", "line", "splineArea", etc.
                type: "column",
                dataPoints: [
                    { label: "apple",  y: 10  },
                    { label: "orange", y: 15  },
                    { label: "banana", y: 25  },
                    { label: "mango",  y: 30  },
                    { label: "grape",  y: 28  }
                ]
            }
            ]
        });
        chart.render();
   
       
        script;
        return [
                "chart"=>[
                    "src"=>'https://canvasjs.com/assets/script/canvasjs.min.js'
                ],
                "script"=>[
                    "text"=>$script
                    
                    
                ]
                
        ];
    }

    function styles(){
        return [
            
            ];
    }

    function render(){
        if($this->reportBuilder->error==''){
            $table ="<div>
            <div id='chartContainer' style='height: 300px; width: 100%;'></div>
          </div>";
             
       
        

        
        return $table ;
        }
        else{
            return "<span style='color:red'>".$this->reportBuilder->error."</span>";
        }
    }
    

}