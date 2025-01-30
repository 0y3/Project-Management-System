<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $tbl = 'users';
    public function up()
    {
        if (! Schema::hasColumn($this->tbl, 'deleted_at')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->softDeletes(); }); }
        if (! Schema::hasColumn($this->tbl, 'created_by')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->foreignId('created_by')->nullable()->constrained('users'); }); }
        if (! Schema::hasColumn($this->tbl, 'updated_by')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->foreignId('updated_by')->nullable()->constrained('users'); }); }
        if (! Schema::hasColumn($this->tbl, 'deleted_by')) {
            Schema::table($this->tbl, function (Blueprint $table) { $table->foreignId('deleted_by')->nullable()->constrained('users'); }); }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tbl,function (Blueprint $table) {
            $table->dropIfExists('deleted_at');
            $table->dropIfExists('created_by');
            $table->dropIfExists('updated_by');
            $table->dropIfExists('deleted_by');
        });
    }
};
