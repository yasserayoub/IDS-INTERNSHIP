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
    Schema::create('TicketPriorities', function (Blueprint $table) {

        $table->id('Id');

    $table->string('Name');

     $table->integer('Level');

        $table->text('Description')->nullable();

    $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();
    });
}
public function down(): void
{
    Schema::dropIfExists('TicketPriorities');
}
};
