<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBuilderDashboard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_builder_dashboards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('layout')->nullable();
            $table->text('filters')->nullable();
            $table->string('visibility')->nullable();
            $table->string('token')->nullable();
            $table->string('uuid_token')->nullable();
            $table->string('connection')->nullable();
            $table->enum('status', ['1', '0']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_builder_dashboards');
    }
}
