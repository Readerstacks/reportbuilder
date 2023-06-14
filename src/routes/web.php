<?php
 
 if(config("reportconfig.builder_url"))
 Route::get(config("reportconfig.builder_url"), 'Aman5537jains\ReportBuilder\Http\Controllers\ReportManagerController@builder');

 if(config("reportconfig.dashboar_builder_url"))
 Route::get(config("reportconfig.dashboar_builder_url"), 'Aman5537jains\ReportBuilder\Http\Controllers\ReportManagerController@dashBoardBuilder');
 
 
Route::group(['namespace' => 'Aman5537jains\ReportBuilder\Http\Controllers',"prefix"=>"report-manager"], function(){
 
    Route::get('report/{id}', 'ReportManagerController@showReport');
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