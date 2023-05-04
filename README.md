<p align="center">
    <a href="https://github.com/readerstacks" target="_blank">
        <img src="https://i0.wp.com/readerstacks.com/wp-content/uploads/2021/10/Screenshot_2021-10-30_at_11.21.33_AM-removebg-preview-5-1.png?w=500&ssl=1" height="100px">
    </a>
    <h1 align="center">Laravel Report Generator as Metabase By readerstacks.com</h1>
    <br>
</p>

<img width="1664" alt="Screenshot 2023-05-04 at 12 09 50 PM" src="https://user-images.githubusercontent.com/94598275/236130169-d88d3169-f78f-4e2d-9023-9ff9e568a7a5.png">
<img width="1673" alt="Screenshot 2023-05-04 at 12 56 58 PM" src="https://user-images.githubusercontent.com/94598275/236138954-ea63c39a-00fc-47bc-a457-25d34ad6ca2a.png">
<img width="1660" alt="Screenshot 2023-05-04 at 12 57 05 PM" src="https://user-images.githubusercontent.com/94598275/236138968-a1bf51fc-5d7e-4e7a-a731-1c5f87d72843.png">




Create Any report easily with laravel report builder same as metabase.

 
 
For license information check the [LICENSE](LICENSE.md)-file.

Features
--------

- Generate any report in chart, table format easily like metabase.


Installation
------------

### 1 - Dependency

The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:

```shell
composer require readerstacks/reportmanager
```

> **Note**: If you are using Laravel 5.5, the steps 2  for providers and aliases, are unnecessaries. QieryMigrations supports Laravel new [Package Discovery](https://laravel.com/docs/5.5/packages#package-discovery).

### 2 - Provider

You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`

```php
<?php

return [
    // ...
    'providers' => [
        Aman5537jains\ReportBuilder\ReportBuilderServiceProvider::class,
        // ...
    ],
    // ...
];
```

#### Lumen

Go to `/bootstrap/app.php` file and add this line:

```php
<?php
// ...

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

// ...

$app->register(Aman5537jains\ReportBuilder\ReportBuilderServiceProvider::class);

// ...

return $app;
```

 

### 3 Configuration

#### Publish config

In your terminal type

```shell
php artisan vendor:publish --provider="Aman5537jains\ReportBuilder\ReportBuilderServiceProvider"
```

#### Run Migration

In your terminal type

```shell
php artisan migrate
```


  
Usage
-----

### Laravel Usage

Access directly 
http://localhost/report-manager/builder

if you want to use in code anywhere then 

```php

  (new \Aman5537jains\ReportBuilder\ReportGenerator())->render();

```

Customization
-----

### Add New Layout

You can create any custom layout by registering the class in `reportconfig.php` file

```php
<?php 
return [
  "layouts"=>[
        "table"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout"],
        "table2"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout"],
        "Number"=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\NumberViewLayout"],
        'Chart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\ChartLayout","settings"=>    ["type"=>"pie","chart_label"=>"Users","label_column"=>"labels","data_column"=>"data","colors_column"=>"colors"]],
        'CanvasChart'=>["class"=>"\Aman5537jains\ReportBuilder\Layouts\CanvasChartLayout","settings"=>["type"=>"pie","chart_label"=>"Users","label_column"=>"labels","data_column"=>"data","colors_column"=>"colors"]],
        
         
        
    ],
...

    ```

if you look at class `\Aman5537jains\ReportBuilder\Layouts\TableLayout\TableLayout` then you customize it or you can register your class as below

```php 

<?php
namespace Aman5537jains\ReportBuilder\Layouts\TableLayout;

use Aman5537jains\ReportBuilder\Layouts\BaseLayout;

class TableLayout extends BaseLayout
{
   
    function scripts(){
        
        $script = <<<SCRIPT
             new DataTable('.tbl_report');
        SCRIPT;

        return [
            'datatable'=>[
                "src"=>'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js'
            ],
            'script'=>[
                'text'=>$script
                ]
        ];
    }

    function styles(){
        return [
            'datatable'=>[
                "src"=>"https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
            ]
            ];
    }

    function render(){
        if($this->reportBuilder->error==''){
        $table=' ';
        // foreach($this->reportBuilder->report->variables  as $name=>$var){
        //     $table.=$var['obj']->render();

        // }
        $table .=  ' 
        <style>
        .tbl_report{
            border-collapse: collapse;
            width: 100%;
        }
         .tbl_report td,  .tbl_report th {
            border: 1px solid #ddd;
            padding: 8px;
          }
          .tbl_report th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #000;
            color: white;
          }
        </style>
        
        <table id="tbl_report" class="tbl_report">';
        $table .=  '<thead><tr>';
  
        foreach($this->reportBuilder->columns as $column){

            $table.=   '<th>'.$column->render().' </th>';
        }
        $table .=  '</tr><thead><tbody>';
        foreach($this->reportBuilder->rows as $row){
            $table.=   '<tr>';
            foreach($this->reportBuilder->columns as $column){

                $table.=   '<td>'.$row->render($column->name()).' </td>';
            }
            $table.=   '</tr>';
        }
        
        $table.= "</tbody></table>";
        

        
        return $table ;
        }
        else{
            return "<span style='color:red'>".$this->reportBuilder->error."</span>";
        }
    }
    

}
```
 
