<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('project_name');
            $table->text('description')->nullable();
            $table->enum('status', [
                'Planning', 
                'In Progress', 
                'On Hold', 
                'Completed'
            ])->default('Planning');
            $table->enum('priority', [
                'Low', 
                'Medium', 
                'High'
            ])->default('Medium');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
            $table->datetime('deleted_at')->nullable();
            $table->string('record_status')->nullable()->default('active');
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
