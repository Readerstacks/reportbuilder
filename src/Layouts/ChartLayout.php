<?php

namespace Aman5537jains\ReportBuilder\Layouts;

use function GuzzleHttp\json_encode;

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

        foreach ($this->reportBuilder->rows as $row) {
            if (isset($row->row->{$this->layoutSettings['label_column']})) {
                $labels[] = $row->row->{$this->layoutSettings['label_column']};
            }
            if (isset($row->row->{$this->layoutSettings['data_column']})) {
                $data_column[] = $row->row->{$this->layoutSettings['data_column']};
            }
            if (isset($row->row->{$this->layoutSettings['colors_column']})) {
                $colors_column[] = $row->row->{$this->layoutSettings['colors_column']};
            }
        }
        $labels = json_encode($labels);
        $data_column = json_encode($data_column);
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
                      datasets: [{
                        label:  '{$this->layoutSettings['chart_label']}',
                        data: $data_column,
                        backgroundColor: $colors_column,
                        hoverOffset: 4
                      }]
                    };

                    new Chart(ctx, {
                      type:  '{$this->layoutSettings['type']}',
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
            $table = "<div style='height:400px;width:100%;display:flex;justify-content:center;alighn-items:center'  >
            <canvas   id='myChart'></canvas>
          </div>";

            return $table;
        } else {
            return "<span style='color:red'>".$this->reportBuilder->error.'</span>';
        }
    }
}
