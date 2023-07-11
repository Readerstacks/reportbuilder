<?php

namespace Aman5537jains\ReportBuilder\Editors;

class Editor
{
    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }
}
