<?php

namespace Aman5537jains\ReportBuilder\Http\Controllers;

use Aman5537jains\ReportBuilder\Model\ReportBuilderDashboard;
use Aman5537jains\ReportBuilder\Model\ReportBuilderQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportManagerController extends Controller
{
    private $CmsManager;

    public function __construct()
    {
    }

    public function builder()
    {
        return (new \Aman5537jains\ReportBuilder\ReportGenerator())->render();
    }

   public function dashBoardBuilder()
   {
       return (new \Aman5537jains\ReportBuilder\ReportGenerator())->dashboardRender();
   }

    public function getAllRepors()
    {
        return response()->json(['data'=>ReportBuilderQuestion::get()]);
    }

    public function showDashboard($id)
    {
        return view('ReportBuilder::dashboard-view', ['dashboardid'=>$id]);
    }

    public function showReport($id)
    {
        $report = ReportBuilderQuestion::where('uuid_token', $id)->first();

        return view('ReportBuilder::show', ['id'=>$id, 'report'=>$report]);
    }

    public function getSettings()
    {
        $filter_inputs = config('reportconfig.filter_inputs');
        $layouts = config('reportconfig.layouts');

        return response()->json(['filters'=>$filter_inputs, 'layouts'=>$layouts]);
    }

    public function getInput(Request $request)
    {
        $filter_inputs = config('reportconfig.filter_inputs');
        $input = $filter_inputs[$request->get('input')];
        if ($input['class']) {
            $config = $request->get('config', '[]');
            $config = json_decode($config, true);

            $inpclass = new $input['class']($config, $input['settings']);

            return ['scripts'=>$inpclass->scripts(), 'styles'=>$inpclass->styles(), 'html'=>$inpclass->render()];
        }
    }

    public function generate_uuid()
    {
        $token = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0x0C2F) | 0x4000,
            mt_rand(0, 0x3FFF) | 0x8000,
            mt_rand(0, 0x2AFF),
            mt_rand(0, 0xFFD3),
            mt_rand(0, 0xFF4B)
        );
        if (ReportBuilderQuestion::where('uuid_token', $token)->count() <= 0) {
            return $token;
        } else {
            return $this->generate_uuid();
        }
    }

    public function saveDashboard(Request $request)
    {
        $filters = $request->get('filters');

        $sql = $request->get('layout');
        $id = (int) $request->get('dashboard_id', 0);

        if ($id <= 0) {
            $ReportBuilderDashboard = new ReportBuilderDashboard();
            $ReportBuilderDashboard->uuid_token = $this->generate_uuid();
        } else {
            $ReportBuilderDashboard = ReportBuilderDashboard::find($request->get('dashboard_id'));
        }

        $ReportBuilderDashboard->title = $request->get('title');
        // $ReportBuilderDashboard->sql_query=$sql;
        $ReportBuilderDashboard->filters = $filters;
        $ReportBuilderDashboard->visibility = $request->get('visibility', 'Public');
        $ReportBuilderDashboard->connection = $request->get('connection', '');

        $ReportBuilderDashboard->token = $request->get('token', 'Public');
        $ReportBuilderDashboard->filters = $filters;
        $ReportBuilderDashboard->layout = $request->get('layout');
        if (empty($ReportBuilderDashboard->uuid_token)) {
            $ReportBuilderDashboard->uuid_token = $this->generate_uuid();
        }
        $ReportBuilderDashboard->save();

        return ['status'=>true, 'data'=>$ReportBuilderDashboard];
    }

    public function getDashboardById(Request $request)
    {
        $report = ReportBuilderDashboard::where('uuid_token', $request->dashboardId)->first();
        $reportManager = (new \Aman5537jains\ReportBuilder\ReportBuilder())

        ->setReportCustom([
            'variables'=> json_decode($report->filters, true),
            'query'    => 'select now()',
            'layout'   => '',
        ])->build();
        $inputs = [];

        foreach ($reportManager->report->variables as $name=>$var) {
            $inpclass = $var['obj'];
            $inputs[$name] = ['input_type'=>$var['type'], 'scripts'=>$inpclass->scripts(), 'styles'=>$inpclass->styles(), 'html'=>$inpclass->render()];
        }
        $report->inputs = $inputs;

        return $report;
    }

    public function saveReport(Request $request)
    {
        $filters = $request->get('filters');

        $sql = $request->get('sql');
        $id = (int) $request->get('report_id', 0);

        if ($id <= 0) {
            $ReportBuilderQuestion = new ReportBuilderQuestion();
            $ReportBuilderQuestion->uuid_token = $this->generate_uuid();
        } else {
            $ReportBuilderQuestion = ReportBuilderQuestion::find($request->get('report_id'));
        }

        $ReportBuilderQuestion->title = $request->get('title');
        $ReportBuilderQuestion->sql_query = $sql;
        $ReportBuilderQuestion->filters = $filters;
        $ReportBuilderQuestion->visibility = $request->get('visibility', 'Public');
        $ReportBuilderQuestion->connection = $request->get('connection', '');

        $ReportBuilderQuestion->token = $request->get('token', 'Public');
        $ReportBuilderQuestion->filters = $filters;
        $ReportBuilderQuestion->layout = $request->get('layout');
        if (empty($ReportBuilderQuestion->uuid_token)) {
            $ReportBuilderQuestion->uuid_token = $this->generate_uuid();
        }
        $ReportBuilderQuestion->save();

        return ['status'=>true, 'data'=>$ReportBuilderQuestion];
    }

    public function getReportDetail(Request $request)
    {
        $report = ReportBuilderQuestion::find($request->reportId);

        return $report;
    }

    public function getReportById(Request $request)
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

    public function getReportCustom(Request $request)
    {
        $filters = $request->get('filters');
        $sql = $request->get('sql');
        $layout = $request->get('layout');
        $connection = $request->get('connection');

        //     // else{
        //         $report=  ReportBuilderQuestion::find($request->reportId);
        //         $sql=$report->sql_query;
        //         $filters=$report->filters;
        //         $layout=$report->layout;
        // //    }

        return $this->getReport($sql, $filters, $layout, (object) [
            'connection'=> $connection,
        ]);
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
                'title' => @$reportManager->title,
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
}
