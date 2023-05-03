<?php
namespace Aman5537jains\ReportBuilder\Formatters;

use Aman5537jains\ReportBuilder\Formatters\BaseColumnNameFormatter;

class ColumnNameFormatter  extends BaseColumnNameFormatter
{

    public function format(){
        if(empty($this->column["title"]))
            $this->column["title"]=ucfirst(str_replace("_"," ",$this->column['name']));
    }   
}