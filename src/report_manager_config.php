<?php

 

return [

  "builder_url"=>"report-manager/builder", //if defined as false means not accessbile
  "dashboar_builder_url"=>"report-manager/dashboar-builder",
  
  "layouts"=>[
        "table"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout"],
        // "table2"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout"],
        "Number"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\NumberViewLayout"],
        'Chart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\ChartLayout","settings"=>["type"=>"pie","chart_label"=>"Users","label_column"=>"labels","data_column"=>"data","colors_column"=>"colors"]],
        'CanvasChart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\CanvasChartLayout","settings"=>["type"=>[
                        "type"=>"dropdown","options"=>["column","pie",'splineArea','doughnut','line'],"value"=>"column"
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
            "type"=>"dropdown","options"=>["text","number",'date','datetime','color','email','password'],"value"=>"text"]
        ]],
        "Number"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\TextInput::class,"settings"=>["type"=>"number"]],
        "Date Range"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\DateFilterInput::class,"settings"=>["column"=>"created_at",'timepicker'=>"false"]],
        "Select 2 Picker"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\Select2PickerFilterInput::class,"settings"=>["url"=>"https://api.github.com/search/repositories?term=sel&_type=query&q=sel"]],
        "Touchpoint"=>[
            "class"=>\Aman5537jains\ReportBuilder\Inputs\TouchPointSelectInput::class,"settings"=>["model"=>\App\Models\TableManager::class]
        ]
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