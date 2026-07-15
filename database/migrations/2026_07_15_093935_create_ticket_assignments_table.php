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
    Schema::create('TicketAssignments', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('TicketId'); //fk

        $table->unsignedBigInteger('AssignedToUserId');//fk

        $table->unsignedBigInteger('AssignedByUserId');//fk

        $table->text('AssignmentReason')->nullable();

        $table->timestamp('AssignedAt')->useCurrent();

        $table->timestamp('UnassignedAt')->nullable();

        $table->boolean('IsCurrent')->default(true);

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');

        $table->foreign('AssignedToUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('restrict');

        $table->foreign('AssignedByUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('restrict');
    });
}

public function down(): void
{
    Schema::dropIfExists('TicketAssignments');
}
};
