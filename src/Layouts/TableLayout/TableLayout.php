<?php
namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

use Aman5537jains\ReportBuilder\Layouts\BaseLayout;

class TableLayout extends BaseLayout
{   

    public $rederer="server";
    function scripts(){
        $filename="data";
        if(@$this->reportBuilder->report->object->title){
            $filename=$this->reportBuilder->report->object->title;
        }
        $script = <<<SCRIPT
                new DataTable('.tbl_report');
                
                (function($){



                    $.fn.extend({
                        tableHTMLExport: function(options) {
                
                            var defaults = {
                                separator: ',',
                                newline: '\\r\\n',
                                ignoreColumns: '',
                                ignoreRows: '',
                                type:'csv',
                                htmlContent: false,
                                consoleLog: false,
                                trimContent: true,
                                quoteFields: true,
                                filename: 'tableHTMLExport.csv',
                                utf8BOM: true,
                                orientation: 'p' //only when exported to *pdf* "portrait" or "landscape" (or shortcuts "p" or "l")
                            };
                            var options = $.extend(defaults, options);
                
                
                            function quote(text) {
                                return '"' + text.replace('"', '""') + '"';
                            }
                
                
                            function parseString(data){
                
                                if(defaults.htmlContent){
                                    content_data = data.html().trim();
                                }else{
                                    content_data = data.text().trim();
                                }
                                return content_data;
                            }
                
                            function download(filename, text) {
                                var element = document.createElement('a');
                                element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(text));
                                element.setAttribute('download', filename);
                
                                element.style.display = 'none';
                                document.body.appendChild(element);
                
                                element.click();
                
                                document.body.removeChild(element);
                            }
                
                            /**
                             * Convierte la tabla enviada a json
                             * @param el
                             * @returns {{header: *, data: Array}}
                             */
                            function toJson(el){
                
                                var jsonHeaderArray = [];
                                $(el).find('thead').find('tr').not(options.ignoreRows).each(function() {
                                    var tdData ="";
                                    var jsonArrayTd = [];
                
                                    $(this).find('th').not(options.ignoreColumns).each(function(index,data) {
                                        if ($(this).css('display') != 'none'){
                                            jsonArrayTd.push(parseString($(this)));
                                        }
                                    });
                                    jsonHeaderArray.push(jsonArrayTd);
                
                                });
                
                                var jsonArray = [];
                                $(el).find('tbody').find('tr').not(options.ignoreRows).each(function() {
                                    var tdData ="";
                                    var jsonArrayTd = [];
                
                                    $(this).find('td').not(options.ignoreColumns).each(function(index,data) {
                                        if ($(this).css('display') != 'none'){
                                            jsonArrayTd.push(parseString($(this)));
                                        }
                                    });
                                    jsonArray.push(jsonArrayTd);
                
                                });
                
                
                                return {header:jsonHeaderArray[0],data:jsonArray};
                            }
                
                
                            /**
                             * Convierte la tabla enviada a csv o texto
                             * @param table
                             * @returns {string}
                             */
                            function toCsv(table){
                                var output = "";
                                
                                if (options.utf8BOM === true) {                
                                    output += '\ufeff';
                                }
                
                                var rows = table.find('tr').not(options.ignoreRows);
                
                                var numCols = rows.first().find("td,th").not(options.ignoreColumns).length;
                
                                rows.each(function() {
                                    $(this).find("td,th").not(options.ignoreColumns)
                                        .each(function(i, col) {
                                            var column = $(col);
                
                                            // Strip whitespaces
                                            var content = options.trimContent ? $.trim(column.text()) : column.text();
                
                                            output += options.quoteFields ? quote(content) : content;
                                            if(i !== numCols-1) {
                                                output += options.separator;
                                            } else {
                                                output += options.newline;
                                            }
                                        });
                                });
                
                                return output;
                            }
                
                
                            var el = this;
                            var dataMe;
                            if(options.type == 'csv' || options.type == 'txt'){
                
                
                                var table = this.filter('table'); // TODO use $.each
                
                                if(table.length <= 0){
                                    throw new Error('tableHTMLExport must be called on a <table> element')
                                }
                
                                if(table.length > 1){
                                    throw new Error('converting multiple table elements at once is not supported yet')
                                }
                
                                dataMe = toCsv(table);
                
                                if(defaults.consoleLog){
                                    console.log(dataMe);
                                }
                
                                download(options.filename,dataMe);
                
                
                                //var base64data = "base64," + $.base64.encode(tdData);
                                //window.open('data:application/'+defaults.type+';filename=exportData;' + base64data);
                            }else if(options.type == 'json'){
                
                                var jsonExportArray = toJson(el);
                
                                if(defaults.consoleLog){
                                    console.log(JSON.stringify(jsonExportArray));
                                }
                                dataMe = JSON.stringify(jsonExportArray);
                
                                download(options.filename,dataMe)
                                /*
                                var base64data = "base64," + $.base64.encode(JSON.stringify(jsonExportArray));
                                window.open('data:application/json;filename=exportData;' + base64data);*/
                            }else if(options.type == 'pdf'){
                
                                var jsonExportArray = toJson(el);
                
                                var contentJsPdf = {
                                    head: [jsonExportArray.header],
                                    body: jsonExportArray.data
                                };
                
                                if(defaults.consoleLog){
                                    console.log(contentJsPdf,);
                                }
                
                                var doc = new jsPDF(defaults.orientation, 'pt');
                                
                                doc.autoTable(jsonExportArray.header, jsonExportArray.data);
                                doc.save(options.filename);
                
                            }
                            return this;
                        }
                    });
                })(jQuery);
        
                $('#json').on('click',function(){
                    $("#tbl_report").tableHTMLExport({type:'json',filename:'data.json'});
                  })
                  $('#export').on('click',function(){
                    $("#tbl_report").tableHTMLExport({type:'csv',filename:'$filename.csv'});
                  })
                  $('#exportpdf').on('click',function(){
                    $("#tbl_report").tableHTMLExport({type:'pdf',filename:'$filename.pdf'});
                  })
        SCRIPT;

        return [
            'datatable'=>[
                "src"=>'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js'
            ],
            "jsPdf"=>[
                "src"=>"https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"
            ],
            "jsPdfAutoTable"=>[
                "src"=>"https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.min.js"
            ],
            'script'=>[
                'text'=>$script
                ]
        ];
    }

    function styles(){
        return [
                    'datatable'=>[
                        "src"=>"https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
                    ]
                ];
    }

    function jsonResult(){
        return [
            
        ];
    }

    function render(){
        
        if($this->reportBuilder->error==''){
        $table=' ';

     
        // foreach($this->reportBuilder->report->variables  as $name=>$var){
        //     $table.=$var['obj']->render();

        // }
        $table .=  ' 
        <style>
        .tbl_report{
            border-collapse: collapse;
            width: 100%;
        }
         .tbl_report td,  .tbl_report th {
            border: 1px solid #ddd;
            padding: 8px;
          }
          .tbl_report th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #000;
            color: white;
          }
          .exports{
            float:right;
            margin-bottom:2px;
          }
        </style>
        <div class="exports">
            <button id="export" class="btn btn-primary">Export Sheet</button>
            <button id="exportpdf" class="btn btn-secondary">Export PDF</button>
        </div>
        <table id="tbl_report" class="tbl_report table">';
        $table .=  '<thead><tr>';
        $colFormatter  =  isset($this->layoutSettings['column_formatter_class']) && !empty($this->layoutSettings['column_formatter_class'])?$this->layoutSettings['column_formatter_class']:"";
        $colFormatterMethod  =  isset($this->layoutSettings['column_formatter_method']) && !empty($this->layoutSettings['column_formatter_method'])?$this->layoutSettings['column_formatter_method']:"";
        $hide_columns  =  isset($this->layoutSettings['hide_columns']) && !empty($this->layoutSettings['hide_columns'])?$this->layoutSettings['hide_columns']:"";
        $hide_columns_arr=[];
        if(!empty( $hide_columns)){
            $hide_columns_arr = explode(",", $hide_columns);

        }
         
        foreach($this->reportBuilder->columns as $column){
           
            if(!in_array($column->name(),$hide_columns_arr))
            $table.=   '<th>'.$column->render().' </th>';
        }
        $table .=  '</tr><thead><tbody>';
        foreach($this->reportBuilder->rows as $row){
            $table.=   '<tr>';
            foreach($this->reportBuilder->columns as $column){
                if(in_array($column->name(),$hide_columns_arr))
                {
                    continue;
                }
                $value = $row->render($column->name());
                if( $colFormatter!='' && $colFormatterMethod!=''){
                    $value=$colFormatter::$colFormatterMethod($column->name(),$value,$row);
                }
                
            
                $table.=   '<td>'.$value.' </td>';
            }
            $table.=   '</tr>';
        }
        
        $table.= "</tbody></table>";
        

        
        return $table ;
        }
        else{
            return "<span style='color:red'>".$this->reportBuilder->error."</span>";
        }
    }
    

}