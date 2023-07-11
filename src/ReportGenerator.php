<?php

namespace Aman5537jains\ReportBuilder;

class ReportGenerator
{
    public function render()
    {
        return view('ReportBuilder::generator');
    }

    public function dashboardRender()
    {
        return view('ReportBuilder::dashboard-builder');
    }
}
