<?php

namespace Aman5537jains\ReportBuilder;

use Aman5537jains\ReportBuilder\Model\ReportBuilderQuestion;
use PHPHtmlParser\Dom;

class ReportBuilder
{
    public $reportId;
    public $report = null;
    public $results = null;
    public $filters = [];
    public $columns = [];
    public $rows = [];
    public $error = '';
    public $layout;
    public $sql = '';
    public $connection = '';
    public $bindingCounter = 0;
    public $bindings = [];
    public $renderedColumns = [];
    public $renderedRows = [];

    public function __construct()
    {
        $database = config('database');
        $connection = $database['default'];
    }

    public function setReportId($id)
    {
        $this->reportId = $id;

        return $this->report = ReportBuilderQuestion::find($id);
    }

    public function setConnection($connection = '')
    {
        if (!empty($connection)) {
            $this->connection = $connection;
        }

        return $this;
    }

    public function setReportCustom($options = [])
    {
        $this->report = (object) $options;

        return $this;
    }

    public function checkLimit($query){

        $limit_pattern = '/\bLIMIT\b/i';

        // Check if the SQL query contains a LIMIT clause
        if (preg_match($limit_pattern, $query)) {
            return $query;
        } else {
            return $query." limit 10000";
        }
    }

    public function createQuery($html)
    {
        $html = str_replace('[[', '<conditional>', $html);
        $html = str_replace(']]', '</conditional>', $html);
        $html = str_replace('{{', '<variable>', $html);
        $html = str_replace('}}', '</variable>', $html);
        $html = "<sql>$html</sql>";
        $dom = new Dom();
        $dom->loadStr($html);
        // dd((new Options())->setWhitespaceTextNode(false) );
        $dom->setOptions(
            // this is set as the global option level.
            ['whitespaceTextNode' => false]
        );

        $sql = $dom->find('sql');
        $q = '';
        $this->getchilds($sql, $q);

        return $q;
    }

    public function getReportById($request)
    {
        $report = ReportBuilderQuestion::where('uuid_token', $request->reportId)->first();
        if ($report->visibility == 'Protected' && $request->password == $report->token) {
            return $this->getReport($report->sql_query, $report->filters, $report->layout, $report);
        } elseif ($report->visibility == 'Public') {
            return $this->getReport($report->sql_query, $report->filters, $report->layout, $report);
        } else {
            return [
                'sql'   => '',
                'inputs'=> [],
                'title' => 'Access Denied!',
                'layout'=> ['scripts'=>[], 'styles'=>[], 'html'=>['Invalid password']],
            ];
        }
    }

    public function getReport($sql, $filters, $layout = 'table', $reportManager = null)
    {
        try {
            $report = (new \Aman5537jains\ReportBuilder\ReportBuilder())
                ->setConnection($reportManager->connection)
                ->setReportCustom([
                    'variables'=> json_decode($filters, true),
                    'query'    => $sql,
                    'layout'   => json_decode($layout, true),
                    'object'   => @$reportManager,
                ])->build();
            $inputs = [];

            foreach ($report->report->variables as $name=>$var) {
                $inpclass = $var['obj'];
                $inputs[$name] = ['input_type'=>$var['type'], 'scripts'=>$inpclass->scripts(), 'styles'=>$inpclass->styles(), 'html'=>$inpclass->render()];
            }

            return [
                'sql'   => config('debug') || 1 ? $report->sql : '',
                'inputs'=> $inputs,
                'title' => $report->layout->reportTitle(),
                'id'    => @$reportManager->id,
                'layout'=> [
                    'scripts'   => $report->layout->scripts(),
                    'json'      => [],
                    'styles'    => $report->layout->styles(),
                    'html'      => $report->layout->render(),
                ],
            ];
        } catch(\Exception $e) {
            return [
                'sql'   => '',
                'inputs'=> [],
                'title' => '',
                'layout'=> [
                    'scripts'   => [],
                    'json'      => [],
                    'styles'    => [],
                    'html'      => '<span style="color:red"> Error : '.$e->getMessage().' - line '.$e->getLine().' - File -> '.$e->getFile().'</span>',
                ],
            ];
        }
    }

    public function requestC($var)
    {
        $var = trim($var);

        $varArr = explode('.', $var);
        $var = $varArr[0];
        $val = false;
        if (isset($this->report->variables[$var])) {
            if (count($varArr) > 1) {
                $arrays = $this->report->variables[$var]['obj']->queryValue();
                foreach ($varArr as $name) {
                    if (isset($arrays[$name])) {
                        $val = $arrays[$name];
                    }
                }
            } else {
                $val = $this->report->variables[$var]['obj']->queryValue();
            }
        }

        return $val;
    }

    public function setBindings($paramsValue)
    {
        if (is_array($paramsValue)) {
            $paramsValue['sql'];
            $paramsValue['params'];
            foreach ($paramsValue['params'] as $val) {
                $this->bindings[] = $val;
            }
            return $paramsValue['sql'];
        }

        $this->bindings[] = $paramsValue;

        return '?';
    }

    public function getchilds($sql, &$query = '')
    {

        $include = true;
        $queryInner = '';
        foreach ($sql as $s) {

            if ($s->isTextNode()) {
                $queryInner .= $s->text();
                // getchilds( $s->getChildren());
            } elseif (!$s->isTextNode() && $s->hasChildren()) {

                if ($s->getTag()->name() == 'sql') {
                    $this->getchilds($s->getChildren(), $query);
                } elseif ($s->getTag()->name() == 'conditional') {
                    $new = '';
                    $this->getchilds($s->getChildren(), $new);
                    $queryInner .= $new;

                } elseif ($s->getTag()->name() == 'variable') {

                    $output = $this->requestC($s->getChildren()[0]->text());
                    if (!$output) {
                        $include = false;
                    } else {
                        $binding = $this->setBindings($output);

                        $queryInner .= $binding;
                    }
                }
            }
        }
        if ($include) {
            $query .= $queryInner;
        }
    }

    public function build()
    {
        if ($this->report) {
            foreach ($this->report->variables as $name => $vb) {
                $inputClass = $vb['class'];
                $filter = (new $inputClass($vb, $vb['settings']));
                $this->report->variables[$name]['obj'] = $filter;
                $this->report->variables[$name]['value'] = $filter->value;
                $this->report->variables[$name]['rendered'] = $filter->render();
            }

            $this->sql = htmlspecialchars_decode(
                            $this->checkLimit(
                                $this->createQuery(htmlspecialchars($this->report->query))
                            )
                         );

            try {
                $this->results = \DB::connection($this->connection)
                                    ->select($this->sql, $this->bindings);
                $this->processColumnNames();
                $this->processRow();
                $this->buildLayout();

            } catch(\Exception $e) {
                $this->error = $e->getMessage();
                $this->buildLayout();
            }
        }

        return $this;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    public function processColumnNames()
    {
        if (!empty($this->results)) {
            $customizedColumns = [];
            if (isset($this->report->columns) && count(($this->report->columns)) > 0) {
                $customizedColumns = $this->report->columns;
            }
            foreach ($this->results[0] as $col=>$val) {
                $formatter = "\Aman5537jains\ReportBuilder\Formatters\ColumnNameFormatter";
                $column = ['name'=>$col];
                if (isset($customizedColumns[$col])) {
                    $column = $customizedColumns[$col];
                    if (isset($customizedColumns[$col]['formatter'])) {
                        $formatter = $customizedColumns[$col]['formatter'];
                    }
                }
                $processedColumns = new $formatter($column);

                $this->columns[] = $processedColumns;
                $this->renderedColumns[$processedColumns->name()] = $processedColumns->render();
            }
        }
    }

    public function processRow()
    {
        if (!empty($this->results)) {
            $customizedColumns = [];
            $row_processor = "\Aman5537jains\ReportBuilder\Formatters\RowFormatter";
            if (isset($this->report->row_processor)) {
                $row_processor = $this->report->row_processor;
            }
            foreach ($this->results as $col=>$val) {
                $processedRow = new $row_processor($val);

                $this->rows[] = $processedRow;
                $this->renderedRows[] = $processedRow->render();
            }
        }
    }

    public function buildLayout()
    {
        if ($this->report->layout != '') {
            $layout = $this->report->layout;
            $this->layout = (new $layout['class']($this, @$layout['settings']));
        }
    }

    public function render()
    {
        if ($this->results) {
            return  $this->layout->render();
        } else {
            return "<span style='color:red'>".$this->error.'</span>';
        }
    }
}
