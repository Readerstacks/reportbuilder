<?php

namespace Aman5537jains\ReportBuilder\Inputs;

class Select2PickerFilterInput extends ReportInputs
{
    public function scripts()
    {
        return [

            'select2'=> [
                'src'=> 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            ],

            'script'=> [
                'text'=> "  
                $('.select2').select2({
                  ajax: {
                  url: '{$this->settings['url']}',
                  dataType: 'json'
                  }
                });
            ",
            ],

        ];
    }

    public function styles()
    {
        return [
            'select2'=> [
                'src'=> 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            ],
        ];
    }

    public function html()
    {
        $html = "{$this->config['title']} :<select class='select2' name='{$this->name}'>
           <option value='AL'>Alabama</option>
            
           <option value='WY'>Wyoming</option>
         </select>";

        return  $html;
    }
}
