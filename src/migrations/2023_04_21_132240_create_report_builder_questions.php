<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBuilderQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_builder_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title");
            $table->text("sql_query");
            $table->text("layout");
            $table->text("filters");
            $table->string("visibility");
            $table->string("token");
            $table->string("uuid_token");
            $table->string("connection");
            $table->enum("status",['1','0']);
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
        Schema::dropIfExists('report_builder_questions');
    }
}
