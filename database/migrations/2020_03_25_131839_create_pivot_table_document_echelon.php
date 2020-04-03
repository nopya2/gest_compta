<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotTableDocumentEchelon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_echelon', function (Blueprint $table) {
            $table->unsignedBigInteger('document_id')->nullable();
            $table->index('document_id');
            $table->foreign('document_id')->references('id')->on('documents')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('echelon_id')->nullable();
            $table->index('echelon_id');
            $table->foreign('echelon_id')->references('id')->on('echelons')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_echelon');
    }
}
