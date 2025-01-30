<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $tbl = 'projects';
    public function up()
    {
        if (! Schema::hasColumn($this->tbl, 'deleted_at')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->softDeletes(); }); }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tbl,function (Blueprint $table) {
            $table->dropIfExists('deleted_at');
        });
    }
};
