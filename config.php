<?php 

return [
  "layouts"=>[
        "table"=>"",
    ],
    "filter_inputs"=>[
        ["Text"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\TextInput::class,"settings"=>["type"=>"text"]]],
        ["Number"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\TextInput::class,"settings"=>["type"=>"number"]]],
        ["Date Range"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\DateFilterInput::class,"settings"=>[]]],
        ["Select 2 Picker"=>["class"=>\Aman5537jains\ReportBuilder\Inputs\Select2PickerFilterInput::class,"settings"=>[]]],
        
    ]
    
];