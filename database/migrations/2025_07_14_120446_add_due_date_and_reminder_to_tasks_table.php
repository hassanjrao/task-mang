<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateAndReminderToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dateTime('due_datetime')->nullable();
            $table->integer('reminder_offset')->nullable(); // in minutes before due
            $table->json('reminder_methods')->nullable(); // ["email", "web", "sound", "tts"]
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
            $table->dropColumn('due_datetime');
            $table->dropColumn('reminder_offset');
            $table->dropColumn('reminder_methods');
        });
    }
}
