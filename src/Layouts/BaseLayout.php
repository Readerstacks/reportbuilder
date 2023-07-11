<?php

namespace Aman5537jains\ReportBuilder\Layouts;

class BaseLayout implements LayoutInterface
{
    public $reportBuilder;
    public $layoutSettings;

    public function __construct($reportBuilder, $settings = [])
    {
        $this->layoutSettings = $settings;
        if (empty($reportBuilder)) {
            throw new \Exception('table cannot be empty');
        }
        $this->reportBuilder = $reportBuilder;
    }

    public function scripts()
    {
        return [];
    }

    public function styles()
    {
        return [];
    }

    public function jsonResult()
    {
        return [
            'col' => $this->reportBuilder->renderedColumns,
            'rows'=> $this->reportBuilder->renderedRows,
        ];
    }

    public function view($file)
    {
    }

    public function showNoData()
    {
        if (count($this->reportBuilder->rows) <= 0) {
            return  view('ReportBuilder::no-data')->render();
        }

        return null;
    }

    public function render()
    {
        if ($this->reportBuilder->error == '') {
            $noData = $this->showNoData();

            if ($noData != null) {
                return $noData;
            }
            $table = '<form>';
            foreach ($this->reportBuilder->report->variables  as $name=>$var) {
                $table .= $var['rendered'];
            }
            $table .= '</form>
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
            </style>
            
            <table class="tbl_report">';
            $table .= '<tr>';

            foreach ($this->reportBuilder->columns as $column) {
                $table .= '<th>'.$column->render().' </th>';
            }
            foreach ($this->reportBuilder->rows as $row) {
                $table .= '<tr>';
                foreach ($this->reportBuilder->columns as $column) {
                    $table .= '<td>'.$row->render($column->name()).' </td>';
                }
                $table .= '</tr>';
            }

            $table .= '</tR></table>';

            return $table;
        } else {
            return "<span style='color:red'>".$this->error.'</span>';
        }
    }
}
