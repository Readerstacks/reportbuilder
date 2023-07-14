<?php

namespace Aman5537jains\ReportBuilder\Layouts;

 

class ChartLayout extends BaseLayout
{
    public $rederer = 'client';

    public function settingBuilder()
    {
        return [
            'html'  => '',
            'script'=> '',
        ];
    }

    public function scripts()
    {
        //  dd($this->layoutSettings);
        // {$this->layoutSettings['type']}
        // $this->reportBuilder->columns
        $labels = [];
        $data_column = [];
        $colors_column = [];

        $allColumns = [];
        $allRows    = [];
        $dataNames  = [];
        $columns =  explode(",",$this->layoutSettings['data_column']); // Y AXIS
        $rows =  explode(",",$this->layoutSettings['label_column']); //X AXIS
        $xAxis = [];
     
            foreach ($this->reportBuilder->rows as $row) {
                if(!isset($xAxis[$row->row->{$rows[0]}])){
                    $xAxis[$row->row->{$rows[0]}]=[];
                }
                $col='data';
                if(isset($rows[1])){
                   
                    $col=$row->row->{$rows[1]};
                }
                $xAxis[$row->row->{$rows[0]}][$col]  = $row->row->{$columns[0]};
                $dataNames[$col]=$col;
            }

         foreach($xAxis as $x=>$value){
            $allRows[]=$x;
            foreach($dataNames as $dataName){
                if(!isset($value[$dataName])){
                    $xAxis[$x][$dataName]=0 ;
                }
            }
            
         }   
         foreach($dataNames  as $k=>$val){
            $values=[];
            foreach($xAxis as $v){
               $values[]= $v[$k];
            }
            $allColumns[]=["label"=>$k,"data"=>$values];
         }
        
         
      
       $allRows =array_keys($xAxis);

        foreach ($this->reportBuilder->rows as $row) {
            if (isset($row->row->{$this->layoutSettings['colors_column']})) {
                $colors_column[] = $row->row->{$this->layoutSettings['colors_column']};
            }
        }
        
        // dd($allRows,$allColumns);
        $colors=["red","green","yellow","blue","orange","black","#dc3545",'#18833f','#927a0e'];
        if(count($labels)>0 && count($colors_column)<=0){
            if(!empty($this->layoutSettings['colors_column']))
            {
                $colors_column = explode(",",$this->layoutSettings['colors_column']);
            }
            else{
                $count=count($labels);
                for($i=0;$i<$count;$i++){
                    $colors_column[] =  $colors[$i];
                }
            }
        }
        $labels = json_encode($allRows);
        $data_column = json_encode($allColumns);
        $colors_column = json_encode($colors_column);
        
        return [
            'chart'=> [
                'src'=> 'https://cdn.jsdelivr.net/npm/chart.js',
            ],
            'script'=> [
                'text'=> "
                    
                    const ctx = document.getElementById('myChart');
                    const data = {
                      labels: $labels,
                      datasets:$data_column
                    };

                    new Chart(ctx, {
                      type:  '{$this->layoutSettings['type']['value']}',
                      data:  data
                    });",
            ],

        ];
    }

    public function styles()
    {
        return [

        ];
    }

    public function render()
    {
        if ($this->reportBuilder->error == '') {
            $table = "<div style='max-height:400px;width:100%;display:flex;justify-content:center;alighn-items:center'  >
            <canvas   id='myChart'></canvas>
          </div>";

            return $table;
        } else {
            return "<span style='color:red'>".$this->reportBuilder->error.'</span>';
        }
    }
}
