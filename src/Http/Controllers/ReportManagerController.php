<?php
namespace Aman5537jains\ReportBuilder\Http\Controllers;

use Aman5537jains\ReportBuilder\Model\ReportBuilderDashboard;
use Aman5537jains\ReportBuilder\Model\ReportBuilderQuestion;
use Aman5537jains\ReportBuilder\Model\ReportBuilderQuestionModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 
use Illuminate\Support\Facades\Validator;
 

class ReportManagerController extends Controller
{
    private $CmsManager;

    function __construct(){
       
        
    }
    function builder(){ 
        return (new \Aman5537jains\ReportBuilder\ReportGenerator())->render(); 
   }
   function dashBoardBuilder(){ 
    return (new \Aman5537jains\ReportBuilder\ReportGenerator())->dashboardRender(); 
    }
    function getAllRepors(){
        return response()->json(['data'=>ReportBuilderQuestion::get()]); 
    }
    
    function showDashboard($id){
         
        return view("ReportBuilder::dashboard-view",['dashboardid'=>$id]);

    }
    function showReport($id){
        $report=  ReportBuilderQuestion::where("uuid_token",$id)->first();
        
        return view("ReportBuilder::show",['id'=>$id,'report'=>$report]);

    }

    function getSettings(){
        $filter_inputs  = config("reportconfig.filter_inputs");
        $layouts  = config("reportconfig.layouts");
 
        return response()->json(['filters'=>$filter_inputs,'layouts'=>$layouts]);

    }

    function getInput(Request $request){
        $filter_inputs  = config("reportconfig.filter_inputs");
        $input =$filter_inputs[$request->get("input")];
        if($input['class']){
           $config=  $request->get("config",'[]');
           $config= json_decode($config,true);

           $inpclass= new $input['class']($config,$input['settings']);

           return ["scripts"=>$inpclass->scripts(),"styles"=>$inpclass->styles(),"html"=>$inpclass->render()];
        }
 
    }
    function generate_uuid() {

        $token= sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );
        if(ReportBuilderQuestion::where("uuid_token",$token)->count()<=0){
            return $token;
        }
        else{
            return $this->generate_uuid();

        }
    
    }
    
    function saveDashboard(Request $request){
        $filters =$request->get("filters");
      
        $sql =$request->get("layout");
        $id=(int)$request->get("dashboard_id",0);

        if(  $id<=0){
            $ReportBuilderDashboard= new ReportBuilderDashboard();
            $ReportBuilderDashboard->uuid_token=$this->generate_uuid();
        }
        else
        $ReportBuilderDashboard=   ReportBuilderDashboard::find($request->get("dashboard_id"));

        $ReportBuilderDashboard->title=$request->get("title");
        // $ReportBuilderDashboard->sql_query=$sql;
        $ReportBuilderDashboard->filters=$filters;
        $ReportBuilderDashboard->visibility=$request->get("visibility","Public");
        $ReportBuilderDashboard->connection=$request->get("connection",'');
        
        $ReportBuilderDashboard->token=$request->get("token","Public");
        $ReportBuilderDashboard->filters=$filters;
        $ReportBuilderDashboard->layout=$request->get("layout");
        if(empty($ReportBuilderDashboard->uuid_token)){
            $ReportBuilderDashboard->uuid_token=$this->generate_uuid();
        }
        $ReportBuilderDashboard->save();
        
        return ["status"=>true ,"data"=>$ReportBuilderDashboard  ];
    }
    function getDashboardById(Request $request){
        $report=  ReportBuilderDashboard::where("uuid_token",$request->dashboardId)->first();
        $reportManager= (new \Aman5537jains\ReportBuilder\ReportBuilder())
        
        ->setReportCustom([
            "variables"=>json_decode($report->filters,true),
            "query"=>  "select now()",
            "layout" =>''
        ])->build();
        $inputs=[];
        
        foreach($reportManager->report->variables as $name=>$var)
        {
            $inpclass= $var['obj'];
            $inputs[$name]=["input_type"=>$var['type'],"scripts"=>$inpclass->scripts(),"styles"=>$inpclass->styles(),"html"=>$inpclass->render()];
        }
        $report->inputs = $inputs;
        return $report;
    }
    function saveReport(Request $request){
        $filters =$request->get("filters");
      
        $sql =$request->get("sql");
        $id=(int)$request->get("report_id",0);

        if(  $id<=0){
            $ReportBuilderQuestion= new ReportBuilderQuestion();
            $ReportBuilderQuestion->uuid_token=$this->generate_uuid();
        }
        else
        $ReportBuilderQuestion=   ReportBuilderQuestion::find($request->get("report_id"));

        $ReportBuilderQuestion->title=$request->get("title");
        $ReportBuilderQuestion->sql_query=$sql;
        $ReportBuilderQuestion->filters=$filters;
        $ReportBuilderQuestion->visibility=$request->get("visibility","Public");
        $ReportBuilderQuestion->connection=$request->get("connection",'');
        
        $ReportBuilderQuestion->token=$request->get("token","Public");
        $ReportBuilderQuestion->filters=$filters;
        $ReportBuilderQuestion->layout=$request->get("layout");
        if(empty($ReportBuilderQuestion->uuid_token)){
            $ReportBuilderQuestion->uuid_token=$this->generate_uuid();
        }
        $ReportBuilderQuestion->save();
        
        return ["status"=>true ,"data"=>$ReportBuilderQuestion  ];
    }
    function getReportDetail(Request $request){
         
        $report=  ReportBuilderQuestion::find($request->reportId);
        return $report;
    }
    function getReportById(Request $request){
         
        $report=  ReportBuilderQuestion::where("uuid_token",$request->reportId)->first();
        if($report->visibility=='Protected' && $request->password==$report->token){
             return $this->getReport($report->sql_query, ($report->filters),$report->layout,$report );
        }
        else if($report->visibility=='Public'){
            return $this->getReport($report->sql_query, ($report->filters),$report->layout,$report );
        }
        else{
            return [
                'sql'=>'',
                "inputs"=>[],
                "title"=>"Access Denied!",
                "layout"=>["scripts"=>[],"styles"=>[],"html"=>["Invalid password"]]
            ];
        }
        
    }

    function getReportCustom(Request $request){
       
        $filters =$request->get("filters");
        $sql =$request->get("sql");
        $layout=$request->get("layout");
        $connection=$request->get("connection");
        
        //     // else{
        //         $report=  ReportBuilderQuestion::find($request->reportId);
        //         $sql=$report->sql_query;
        //         $filters=$report->filters; 
        //         $layout=$report->layout;
        // //    }
    
        return $this->getReport($sql,$filters, $layout,(object)[
            "connection"=>$connection
        ]);
       
    }

    function getReport($sql,$filters,$layout='table',$reportManager=null){
        $report =   (new \Aman5537jains\ReportBuilder\ReportBuilder())
        ->setConnection($reportManager->connection)
        ->setReportCustom([
            "variables"=>json_decode($filters,true),
            "query"=>  $sql,
            "layout" =>json_decode($layout,true),
            "object"=>@$reportManager
        ])->build();
        $inputs=[];
        
        foreach($report->report->variables as $name=>$var)
        {
            $inpclass= $var['obj'];
            $inputs[$name]=["input_type"=>$var['type'],"scripts"=>$inpclass->scripts(),"styles"=>$inpclass->styles(),"html"=>$inpclass->render()];
        }
        
        return [
            'sql'   => $report->sql,
            "inputs"=> $inputs,
            "title" => @$reportManager->title,
            "layout"=>[
                        "scripts"   => $report->layout->scripts(),
                        "json"      => $report->layout->jsonResult(),
                        "styles"    => $report->layout->styles(),
                        "html"      => $report->layout->render()
                    ]
        ];
    }

}
