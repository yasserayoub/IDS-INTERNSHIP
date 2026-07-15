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
    Schema::create('CommentMentions', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('CommentId');

        $table->unsignedBigInteger('MentionedUserId');

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys

        $table->foreign('CommentId')
              ->references('Id')
              ->on('TicketComments')
              ->onDelete('cascade');

        $table->foreign('MentionedUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('CommentMentions');
}
};
