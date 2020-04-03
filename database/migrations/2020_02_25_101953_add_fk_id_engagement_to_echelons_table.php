<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkIdEngagementToEchelonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('echelons', function (Blueprint $table) {
            $table->unsignedBigInteger('engagement_id')->nullable();
            $table->index('engagement_id');
            $table->foreign('engagement_id')->references('id')->on('engagements')
                ->onDelete('set null')
                ->onUpdate('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('echelons', function (Blueprint $table) {
            //
        });
    }
}
