<?php
 
 if(config("reportconfig.builder_url")){
    $conf=config("reportconfig.builder_url");
    $url="report-manager/builder";
    $middleware=[];
    if(gettype($conf)=="array"){
        $url=$conf['url'];
        $middleware=$conf['middleware'];
        
    }
    Route::get($url, 'Aman5537jains\ReportBuilder\Http\Controllers\ReportManagerController@builder')->middleware($middleware);
 }
 if(config("reportconfig.dashboar_builder_url")){
    $conf=config("reportconfig.dashboar_builder_url");
    $url="report-manager/dashboar_builder_url";
    $middleware=[];
    if(gettype($conf)=="array"){
        $url=$conf['url'];
        $middleware=$conf['middleware'];
        
    }
    Route::get( $url, 'Aman5537jains\ReportBuilder\Http\Controllers\ReportManagerController@dashBoardBuilder')->middleware($middleware);;
 
 } 
 if(config("reportconfig.report_view_url")){
    $conf=config("reportconfig.report_view_url");
    $url="report-manager/report/{id}";
    $middleware=[];
    if(gettype($conf)=="array"){
        $url=$conf['url']."/{id}";
        $middleware=$conf['middleware'];
        
    }
     
    Route::get($url, 'Aman5537jains\ReportBuilder\Http\Controllers\ReportManagerController@showReport')->middleware($middleware);
 
 } 
 $conf=config("reportconfig.builder_url");
 
 $middleware=[];
 if(gettype($conf)=="array"){
   
     $middleware=$conf['middleware'];
     
 }

Route::group(['namespace' => 'Aman5537jains\ReportBuilder\Http\Controllers',"prefix"=>"report-manager","middleware"=> $middleware], function(){
 
    // Route::get('report/{id}', 'ReportManagerController@showReport');
    Route::get('dashboard/{id}', 'ReportManagerController@showDashboard');
    Route::get('get-all-reports', 'ReportManagerController@getAllRepors');
    
    Route::get('get-report-detail', 'ReportManagerController@getReportDetail');

    Route::get('get-settings', 'ReportManagerController@getSettings');
    Route::post('get-input', 'ReportManagerController@getInput');
    Route::post('visualize-report', 'ReportManagerController@getReportCustom');
    Route::post('get-report', 'ReportManagerController@getReportById');
    Route::post('save-report', 'ReportManagerController@saveReport');

    Route::post('get-dashboard', 'ReportManagerController@getDashboardById');
    Route::post('save-dashboard', 'ReportManagerController@saveDashboard');
    
    
    
});