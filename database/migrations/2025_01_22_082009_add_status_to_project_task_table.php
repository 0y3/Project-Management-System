<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $tbl = 'project_tasks';
    public function up()
    {
        if (! Schema::hasColumn($this->tbl, 'status')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->enum('status', ['completed','in progress'])->default('in progress'); }); }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tbl,function (Blueprint $table) {
            $table->dropIfExists('status');
        });
    }
};
