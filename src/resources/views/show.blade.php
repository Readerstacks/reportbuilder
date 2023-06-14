<div  style="width:100%" id='output'>
                
                <div id="filters" >
            
            </div>
            
                <div  id="outpuhtml" >
                </div>
    </div>
    <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
   <script src="<?php echo url('/public/ReportBuilder/script.js') ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script>

        let url ="<?php echo url("report-manager"); ?>"
                ReportBuilder.data.url=url
                ReportBuilder.el("#outpuhtml")
                ReportBuilder.elFilter("#filters")
                 
                ReportBuilder.setReportId('{{$id}}','{{$report->visibility}}').getReport();
    </script>