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
    public function up(): void
    {
        if(!Schema::hasTable($this->tbl)){
            Schema::create($this->tbl, function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained();
                $table->string('name');
                $table->longText('description')->nullable();
                $table->date('start_date');
                $table->date('end_date');
                $table->foreignId('assignee_id')->constrained('users');
                $table->timestamps();
                $table->foreignId('created_by')->nullable()->constrained('users');
                $table->foreignId('updated_by')->nullable()->constrained('users');
                $table->foreignId('deleted_by')->nullable()->constrained('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tbl);
    }
};
