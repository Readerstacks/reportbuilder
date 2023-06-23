<?php
namespace Aman5537jains\ReportBuilder\Inputs;
 
use Illuminate\Support\Facades\Log;

class DateFilterInput extends ReportInputs
{ 
      function defaultValue(){
       $default=$this->settings['default']['value'];
        if( $default=="None"){
          return isset($this->config['value']) && !empty($this->config['value']) ? $this->config['value']:"";
        }
        else if( $default=="This Month"){
          return date("Y-m-01")." - ".date("Y-m-t");
        }
        else if( $default=="Today"){
          return date("Y-m-d");
        }
        else if( $default=="Last 7 Days"){
          return date("Y-m-d",strtotime("-7 days"))." - ".date("Y-m-d");
        }
        else if( $default=="Last Month"){
          return date("Y-m-01",strtotime("-1 month"))." - ".date("Y-m-t",strtotime("-1 month"));
        }
        else if( $default=="Last 30 Days"){
          return date("Y-m-d",strtotime("-1 month"))." - ".date("Y-m-d");
        }

        return "";
      }

      function queryValue(){
      
          if($this->value!=''){
            $value = explode(" - ",$this->value);
            $v1=trim($value[0]);
            $v2=trim($value[1]);
            $timeS=($this->settings['timepicker']=='true')?"":" 00:00:00";
            $timeE=($this->settings['timepicker']=='true')?"":" 23:59:59";
              $start= $v1.$timeS;
             $end=  $v2.$timeE;

            return [
              "sql"=>"{$this->settings['column']} >= ? and  {$this->settings['column']} <= ?  ",
              "params"=>[
                $start,$end
              ]
            ];
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
          'script'=>[
            'text'=>"  
              var timepicker= {$this->settings['timepicker']};
              $('.datefilter_{$this->name}').daterangepicker({
                autoUpdateInput: false, 
                timePicker: timepicker,  
                showDropdowns: true,
                ranges: {
                  'Today': [moment(), moment()],
                  'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                  'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                  'This Month': [moment().startOf('month'), moment().endOf('month')],
                  'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                 
               },       
                locale: {
                  'format': 'YYYY-MM-DD',
                },
                opens: 'left'
              }, function(start, end, label) {
                if(timepicker)
                $('.datefilter_{$this->name}').val(start.format('YYYY-MM-DD HH:mm:ss')+' - '+ end.format('YYYY-MM-DD HH:mm:ss'))
                else
                $('.datefilter_{$this->name}').val(start.format('YYYY-MM-DD')+' - '+ end.format('YYYY-MM-DD'))
                
                console.log( start.format('YYYY-MM-DD')  , end.format('YYYY-MM-DD'));
              });
              $('.datefilter_{$this->name}').on('cancel.daterangepicker', function(ev, picker) {
              
                // $('.datefilter_{$this->name}').val('');
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

           $html ="<div class='form-group'>
           <label  >{$this->config['title']}</label>
           <input type='text' autocomplete='off' readonly class='datefilter_{$this->name} form-control'   name='{$this->name}' value='{$this->value}' /></div>";
           return  $html;
       } 

} 


?>