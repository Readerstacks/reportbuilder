<?php
 
Route::group(['namespace' => 'Aman5537jains\ReportBuilder\Http\Controllers',"prefix"=>"report-manager"], function(){
    
    Route::get('report/{id}', 'ReportManagerController@showReport');
    
    Route::get('get-report-detail', 'ReportManagerController@getReportDetail');

    Route::get('get-settings', 'ReportManagerController@getSettings');
    Route::post('get-input', 'ReportManagerController@getInput');
    Route::post('visualize-report', 'ReportManagerController@getReportCustom');
    Route::post('get-report', 'ReportManagerController@getReportById');
    Route::post('save-report', 'ReportManagerController@saveReport');
    
    
});