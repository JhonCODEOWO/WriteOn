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
        Schema::table('note_user', function (Blueprint $table) {
            //Delete fk on the table
            $table->dropForeign(['note_id']);
            $table->dropForeign(['user_id']);

            //Add onDelete operations
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('note_user', function (Blueprint $table) {
            //Delete the actual fk constraint
            $table->dropForeign(['note_id']);
            $table->dropForeign(['user_id']);

            //Revert foreign keys to old state
            $table->foreignUuid('note_id')->references('id')->on('notes');
            $table->foreignUuid('user_id')->references('id')->on('users');
        });
    }
};
