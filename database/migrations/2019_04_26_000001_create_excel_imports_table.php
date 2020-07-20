<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('upload_id');
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('resource');
            $table->string('parent_resource')->nullable();
            $table->json('mapping');
            $table->string('status')->default('waiting');
            //$table->timestamp('undo_at')->nullable();
            //$table->timestamp('retried_at')->nullable();
            $table->timestamps();

            $table->foreign('upload_id')
                  ->references('id')->on('excel_uploads')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excel_imports');
    }
}
