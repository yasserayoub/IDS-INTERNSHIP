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
    Schema::create('Tickets', function (Blueprint $table) {

        $table->id('Id');

        $table->string('ReferenceNumber')->unique();

        $table->unsignedBigInteger('CreatedByUserId');

        $table->unsignedBigInteger('CategoryId');

        $table->unsignedBigInteger('PriorityId');

        $table->unsignedBigInteger('StatusId');

        $table->string('Title');

        $table->text('Description');

        $table->timestamp('ResolvedAt')->nullable();

        $table->timestamp('ClosedAt')->nullable();

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();


        $table->foreign('CreatedByUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('cascade');

        $table->foreign('CategoryId')
              ->references('Id')
              ->on('TicketCategories')
              ->onDelete('restrict');

  $table->foreign('PriorityId')
              ->references('Id')
              ->on('TicketPriorities')
              ->onDelete('restrict');
  $table->foreign('StatusId')
              ->references('Id')
              ->on('TicketStatuses')
              ->onDelete('restrict');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Tickets');
    }
};
