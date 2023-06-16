<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surcharges', function (Blueprint $table) {
            $table->boolean('isGrouped')->default(false);
            $table->bigInteger('idFather')->unsigned()->nullable();
            $table->foreign('idFather')->references('id')->on('surcharges')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surcharges', function (Blueprint $table) {
            $table->dropColumn('isGrouped');
            $table->dropForeign(['idFather']);
            $table->dropColumn('idFather');
        });
    }
};
