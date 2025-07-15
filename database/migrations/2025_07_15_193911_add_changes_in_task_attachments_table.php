<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesInTaskAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_attachments', function (Blueprint $table) {
            // make task_id nullable to allow attachments not associated with any task
            $table->unsignedBigInteger('task_id')->nullable()->change();
            $table->string('file_size')->nullable()->after('original_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_attachments', function (Blueprint $table) {
            //
        });
    }
}
