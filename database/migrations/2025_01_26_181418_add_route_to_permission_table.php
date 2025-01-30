<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $tbl = 'permissions';
    public function up(): void
    {
        if (! Schema::hasColumn($this->tbl, 'route')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->string('route')->nullable(); }); }
        if (! Schema::hasColumn($this->tbl, 'description')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->string('description')->nullable(); }); }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table($this->tbl,function (Blueprint $table) {
            $table->dropIfExists('route');
            $table->dropIfExists('description');
        });
    }
};
