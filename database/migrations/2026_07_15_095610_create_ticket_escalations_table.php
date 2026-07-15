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
    Schema::create('TicketEscalations', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('TicketId');

        $table->unsignedBigInteger('EscalatedByUserId');

        $table->unsignedBigInteger('EscalatedToUserId');

        $table->text('Reason');

        $table->integer('EscalationLevel');

        $table->timestamp('EscalatedAt')->useCurrent();

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');

        $table->foreign('EscalatedByUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('restrict');

        $table->foreign('EscalatedToUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('restrict');
    });
}

public function down(): void
{
    Schema::dropIfExists('TicketEscalations');
}
};
