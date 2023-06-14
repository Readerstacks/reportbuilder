<?php

namespace Aman5537jains\ReportBuilder;

use Aman5537jains\ReportBuilder\Model\ReportBuilderQuestion;
use PHPHtmlParser\Dom;

use Illuminate\Support\Facades\Log;

class ReportBuilder
{

    public $reportId;
    public $report = null;
    public $results = null;
    public $filters = [];
    public $columns=[];
    public $rows=[];
    public $error='';
    public $sql='';
    public $connection='';
    public $bindingCounter=0;
    public $bindings=[];
    public $renderedColumns=[];
    public $renderedRows=[];
    
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
    public function setConnection($connection=''){
        if(!empty($connection)){
            $this->connection=$connection;
        }
        return $this;
    }
    public function setReportCustom($options = [])
    {
        $this->report = (object) $options;
      
        return $this;
    }

    function createQuery($html)
    {

        $html = str_replace("[[", "<conditional>", $html);
        $html = str_replace("]]", "</conditional>", $html);
        $html = str_replace("{{", "<variable>", $html);
        $html = str_replace("}}", "</variable>", $html);
        $html = "<sql>$html</sql>";
        $dom = new Dom;
        $dom->loadStr($html);
        // dd((new Options())->setWhitespaceTextNode(false) );
        $dom->setOptions(
            // this is set as the global option level.
            ['whitespaceTextNode' => false]

        );

        $sql = ($dom->find("sql"));
        $q = "";
        $this->getchilds(($sql), $q);
        return $q;
    }
    function requestC($var)
    {


          $var = trim($var);
  
        if(isset( $this->report->variables[$var])){
            $val = $this->report->variables[$var]['obj']->queryValue();
              // dump($vars[$var]);
              return $val;
        }
       
        return false;
    }

    public function setBindings($paramsValue){
        
        if(is_array($paramsValue)){
            $paramsValue['sql'];
            $paramsValue['params'];
            foreach($paramsValue['params'] as $val)
                $this->bindings[]=$val;
            return $paramsValue['sql'];
        }
        
        $this->bindings[]=$paramsValue;
        return "?";

    }
    function getchilds($sql, &$query = '')
    {
        // echo ""
        // $query="";
        $include = true;
        $queryInner = '';
        foreach ($sql as $s) {
            // dump($s);
            // echo "&nbsp;&nbsp;&nbsp;";
            if ($s->isTextNode()) {
                $queryInner .= ($s->text());
                // getchilds( $s->getChildren());
            } else if (!$s->isTextNode() && $s->hasChildren()) {
                // dump($s->getTag()->name()  );
                if ($s->getTag()->name() == "sql") {
                    $this->getchilds($s->getChildren(), $query);
                } else if ($s->getTag()->name() == "conditional") {

                    $new = '';
                    $this->getchilds($s->getChildren(), $new);
                    $queryInner .= $new;
                    // getchilds( );
                } else if ($s->getTag()->name() == "variable") {
                    //  echo ( );
                    $output=$this->requestC($s->getChildren()[0]->text());
                    if (!$output) {
                        $include = false;
                    } else {
                        
                        $binding=$this->setBindings($output);

                        $queryInner .= $binding;
                    }
                }
            }
        }
        if ($include)
            $query .= $queryInner;
    }

       

    public function build()
    {

        if ($this->report) {

            foreach ($this->report->variables as $name => $vb) {
                 
                $inputClass =  $vb['class'];
                $filter = (new $inputClass($vb, $vb['settings']));
                $this->report->variables[$name]["obj"] = $filter;
                $this->report->variables[$name]["value"] = $filter->value;
                $this->report->variables[$name]["rendered"] = $filter->render();
            }
            
            $this->sql = $this->createQuery($this->report->query);
            try{
                
             $this->results =   \DB::connection($this->connection)->select($this->sql, $this->bindings);
             
             $this->processColumnNames();
             $this->processRow();
             $this->buildLayout();
            }
            catch(\Exception $e){
                
                $this->error= $e->getMessage();
                $this->buildLayout();
            }
        }
        return $this;
    }

    public function setLayout($layout){
            $this->layout=$layout;
            return $this;
    }
    
    public function processColumnNames(){
        if(!empty($this->results)){
            $customizedColumns=[];
            if(isset($this->report->columns) && count(isset($this->report->columns))>0){
                 $customizedColumns =$this->report->columns;
            }
            foreach($this->results[0] as $col=>$val){
                $formatter="\Aman5537jains\ReportBuilder\Formatters\ColumnNameFormatter";
                $column=["name"=>$col];
                if(isset($customizedColumns[$col])){
                    $column  = $customizedColumns[$col];
                    if(isset($customizedColumns[$col]['formatter'])){
                        $formatter=$customizedColumns[$col]['formatter'];
                    }
                }
                $processedColumns=new $formatter($column);

                $this->columns[]=$processedColumns;
                $this->renderedColumns[$processedColumns->name()]=$processedColumns->render();
            } 
        }
    }
    public function processRow(){
        if(!empty($this->results)){
            $customizedColumns=[];
            $row_processor="\Aman5537jains\ReportBuilder\Formatters\RowFormatter";
            if(isset($this->report->row_processor)  ){
                 $row_processor =$this->report->row_processor;
            }
            foreach($this->results as $col=>$val){
                $processedRow=new $row_processor($val);

                $this->rows[]=$processedRow;
                $this->renderedRows[]=$processedRow->render();
            } 
        }
        

    }

    public function buildLayout(){
        if($this->report->layout!=''){
            $layout =  $this->report->layout;
            // $layouts  = config("reportconfig.layouts");
            $this->layout= $layout;  
            $this->layout = (new $this->layout['class']($this,@$layout['settings'])) ;
        }


    }

    public function render()
    {
        
        if ($this->results) {
 
          
            return  $this->layout->render();
            
        }
        else{
            return "<span style='color:red'>".$this->error."</span>";
        }
    }
}
