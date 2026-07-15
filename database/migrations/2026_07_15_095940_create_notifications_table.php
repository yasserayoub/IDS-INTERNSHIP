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
    Schema::create('Notifications', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('UserId');

        $table->unsignedBigInteger('TicketId')->nullable();

        $table->string('Type');

        $table->string('Title');

     $table->text('Message');

        $table->boolean('IsRead')->default(false);

        $table->timestamp('ReadAt')->nullable();

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys

        $table->foreign('UserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('cascade');

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('Notifications');
}
};
