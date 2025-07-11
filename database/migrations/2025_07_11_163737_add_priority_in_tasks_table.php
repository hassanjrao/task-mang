<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriorityInTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {

            $table->foreignId('priority_id')
            ->nullable()
            ->after('assigned_to')
            ->constrained('priorities')
            ->onDelete('cascade');

            $table->foreignId('status_id')
            ->nullable()
            ->after('priority_id')
            ->constrained('task_statuses')
            ->onDelete('cascade');

            $table->foreignId('group_id')
            ->nullable()
            ->after('status_id')
            ->constrained('groups')
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['priority_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['group_id']);

            $table->dropColumn(['priority_id', 'status_id', 'group_id']);
        });
    }
}
