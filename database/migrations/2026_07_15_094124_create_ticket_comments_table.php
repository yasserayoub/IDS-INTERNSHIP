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
    Schema::create('TicketComments', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('TicketId');

        $table->unsignedBigInteger('UserId');

        $table->text('Content');

        $table->boolean('IsInternal')->default(false);

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');

       $table->foreign('UserId')->references('Id')->on('Users')->onDelete('cascade');

    });
}

public function down(): void
{
    Schema::dropIfExists('TicketComments');
}
};
