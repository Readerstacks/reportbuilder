<?php

namespace Aman5537jains\ReportBuilder\Formatters;

class ColumnNameFormatter extends BaseColumnNameFormatter
{
    public function format()
    {
        if (empty($this->column['title'])) {
            $this->column['title'] = str_replace('_', ' ', $this->column['name']);
        }
    }
}
