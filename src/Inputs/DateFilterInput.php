<?php
namespace Aman5537jains\ReportBuilder\Inputs;
 
use Illuminate\Support\Facades\Log;

class DateFilterInput extends ReportInputs
{ 

      function queryValue(){
          if($this->value!=''){
            $value = explode(" - ",$this->value);
            $start= trim($value[0])." 00:00:00";
            $end= trim($value[1])." 23:59:59";
            return "{$this->settings['column']} >= '{$start}' and  {$this->settings['column']} <= '{$end}'  ";

          }
          return  $this->value;
      }
       function scripts(){
        return [
          
          'moment'=>[
              'src'=>'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js'
          ],
          'daterangepicker'=>[
              'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
          ],
          'daterangepicker'=>[
              'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js'
          ],
          'script'=>[
            'text'=>"  
            
              $('.datefilter_{$this->name}').daterangepicker({
                autoUpdateInput: false,   
                showDropdowns: true,
                ranges: {
                  'Today': [moment(), moment()],
                  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month': [moment().startOf('month'), moment().endOf('month')],
                  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
               },       
                locale: {
                  'format': 'YYYY-MM-DD',
                },
                opens: 'left'
              }, function(start, end, label) {
                $('.datefilter_{$this->name}').val(start.format('YYYY-MM-DD')+' - '+ end.format('YYYY-MM-DD'))
                console.log( start.format('YYYY-MM-DD')  , end.format('YYYY-MM-DD'));
              });
            "
          ]
          
        ];
      
       }

       function styles(){
         return [
            'daterangepicker'=>[
                'src'=>'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css'
            ]
            ];
       }
     
       function html(){
           $html ="<span>{$this->config['title']} </span>:<input type='text' class='datefilter_{$this->name}' name='{$this->name}' value='{$this->value}' />";
           return  $html;
       } 

} 


?>