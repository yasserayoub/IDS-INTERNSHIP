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
    Schema::create('TicketHistories', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('TicketId');

        $table->unsignedBigInteger('ChangedByUserId');

        $table->string('FieldName');

        $table->text('OldValue')->nullable();

        $table->text('NewValue')->nullable();

        $table->timestamp('ChangedAt')->useCurrent();

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys constraints

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');

 $table->foreign('ChangedByUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('restrict');
    });
}

public function down(): void
{
    Schema::dropIfExists('TicketHistories');
}
};
