<?php

namespace Aman5537jains\ReportBuilder;

use PHPHtmlParser\Dom;

use Illuminate\Support\Facades\Log;

class ReportGenerator
{
 
    public function render(){
        return view("ReportBuilder::generator");

    }

}
