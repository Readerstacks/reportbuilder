<?php

namespace Aman5537jains\ReportBuilder\Inputs;

class DateFilterInput extends ReportInputs
{
    public function defaultValue()
    {
        $default = isset($this->settings['default']) && $this->settings['default']['value'] ? $this->settings['default']['value'] : '';
        $timeS = ($this->settings['timepicker'] == 'false') ? '' : '00:00:00';
        $timeE = ($this->settings['timepicker'] == 'false') ? '' : '23:59:59';

        if ($default == 'None') {
            return parent::defaultValue();
        } elseif ($default == 'This Month') {
            return date("Y-m-01 $timeS").' - '.date("Y-m-t $timeE");
        } elseif ($default == 'Today') {
            return date("Y-m-d $timeS").' - '.date("Y-m-d $timeE");
        } elseif ($default == 'Last 7 Days') {
            return date("Y-m-d $timeS", strtotime('-7 days')).' - '.date("Y-m-d $timeE");
        } elseif ($default == 'Last Month') {
            return date("Y-m-01 $timeS", strtotime('-1 month')).' - '.date("Y-m-t $timeE", strtotime('-1 month'));
        } elseif ($default == 'Last 30 Days') {
            return date("Y-m-d $timeS", strtotime('-1 month')).' - '.date("Y-m-d $timeE");
        } elseif ($default == 'This Year') {
            return date("Y-01-01 $timeS").' - '.date("Y-m-d $timeE");
        } elseif ($default == 'Last 1 Year') {
            return date("Y-m-d $timeS", strtotime('-1 year')).' - '.date("Y-m-d $timeE");
        }

        return parent::defaultValue();
    }

    public function queryValue()
    {
        if ($this->value != '') {
            $value = explode(' - ', $this->value);
            $v1 = trim($value[0]);
            $v2 = trim($value[1]);
            $timeS = ($this->settings['timepicker'] == 'true') ? '' : ' 00:00:00';
            $timeE = ($this->settings['timepicker'] == 'true') ? '' : ' 23:59:59';
            $mode = isset($this->settings['mode']) ? $this->settings['mode']['value'] : 'AUTO';

            $start = $v1.$timeS;
            $end = $v2.$timeE;
            if ($mode == 'AUTO') {
                return [
                    'sql'   => "{$this->settings['column']} >= ? and  {$this->settings['column']} <= ?  ",
                    'params'=> [
                        $start, $end,
                    ],
                ];
            } else {
                return compact('start', 'end');
            }

            return "{$this->settings['column']} >= '{$start}' and  {$this->settings['column']} <= '{$end}'  ";
        }

        return  $this->value;
    }

     public function scripts()
     {
         return [

             'moment'=> [
                 'src'=> 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js',
             ],

             'daterangepicker'=> [
                 'src'=> 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js',
             ],
             'script'=> [
                 'text'=> "
              var timepicker= {$this->settings['timepicker']};
              $('.datefilter_{$this->name}').daterangepicker({
                autoUpdateInput: false,
                timePicker: timepicker,
                showDropdowns: true,
                ranges: {
                  'Today': [moment().startOf('day'), moment().endOf('day')],
                  'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                  'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                  'Last 30 Days': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
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
            ",
             ],

         ];
     }

     public function styles()
     {
         return [
             'daterangepicker'=> [
                 'src'=> 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',
             ],
         ];
     }

     public function html()
     {
         $html = "<div class='form-group'>
           <label  >{$this->config['title']}</label>
           <input type='text' autocomplete='off' readonly class='datefilter_{$this->name} form-control'   name='{$this->name}' value='{$this->value}' /></div>";

         return  $html;
     }
}
