<?php
use \Aman5537jains\ReportBuilder\Inputs\SelectInput;
 

return [
  
  "builder_url"=>["middleware"=>[],"url"=>"report-manager/builder"], //if defined as false means not accessbile
  "dashboar_builder_url"=>["middleware"=>[],"url"=>"report-manager/dashboar-builder"],
  "report_view_url"=>["middleware"=>[],"url"=>"report-manager/report"],
  "layouts"=>[
        "table"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout","settings"=>[
            "column_formatter_class"=>Aman5537jains\ReportBuilder\Formatters\TableColValueFormatter::class,
            "column_formatter_method"=>"format",
            "hide_columns"=>"",
            'export_report_schema'=>"",
            'export_report_schema_method'=>"",
            'datatable'=>"true"
        ]],
        // "table2"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout"],
        "Number"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\NumberViewLayout"],
    
        'Chart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\ChartLayout","settings"=>["type"=>"pie","chart_label"=>"Users","label_column"=>"labels","data_column"=>"data","colors_column"=>"colors"]],
        'CanvasChart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\CanvasChartLayout","settings"=>["type"=>[
                        "type"=>"dropdown","options"=>["column",'bar',"pie",'splineArea','doughnut','line'],"value"=>"column"
                        ],"chart_label"=>"Users","label_column"=>"labels","data_column"=>"data","colors_column"=>"colors"]
                    ],
        'InestorTouchpoint'=>[
            "class"=>\App\Reports\Layout\InvestorTouchpointBox::class,"settings"=>[]
        ],
        'InvestorTouchpointChart'=>[
            "class"=>\App\Reports\Layout\InvestorTouchpointChart::class,"settings"=>[]
        ] 
                 
        
         
        
    ],
    "filter_inputs"=>[
        "Input"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\TextInput::class,"settings"=>["type"=> 
        [
            "type"=>"dropdown","options"=>["text","number",'date','datetime','color','email','password'],"value"=>"text"],
            
        ] ,"defaultValue"=>""],
        "Number"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\TextInput::class,"settings"=>["type"=>"number"]],
        "Date Range"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\DateFilterInput::class,
        "settings"=>["column"=>"created_at",'timepicker'=>"false",
        "default"=>[  "type"=>"dropdown","options"=>["Today","Last 7 Days",'This Month','Last Month','Last 30 Days',"None"],"value"=>"This Month"],
        // "initial_value_class"=> \Aman5537jains\ReportBuilder\Inputs\DateFilterInput::class,
        // "initial_value_method"=> "value",
         
        
        ]],
        "Select 2 Picker"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\Select2PickerFilterInput::class,"settings"=>["url"=>"https://api.github.com/search/repositories?term=sel&_type=query&q=sel"]],
        
        "SelectInput"=>["class"=>SelectInput::class,"settings"=>[
            "model"=>\App\Models\User::class,
            "columnid"=>"id",
            "columnvalue"=>"title",
            "filterClass"=>SelectInput::class,
            "filterMethod"=>"filter"
            ]]
    ],
    
    'scripts'=>[
        'jquery'=>[
            'src'=>'https://cdn.jsdelivr.net/jquery/latest/jquery.min.js'
        ],
        'moment'=>[
            'src'=>'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js'
        ],
        'daterangepicker'=>[
            'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
        ],
        'daterangepicker'=>[
            'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
        ],
        
    ],
    "styles"=>[
        'daterangepicker'=>[
            'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css'
        ]
    ]
    
];