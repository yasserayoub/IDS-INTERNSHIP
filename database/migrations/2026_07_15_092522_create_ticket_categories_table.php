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
    Schema::create('TicketCategories', function (Blueprint $table) {

        $table->id('Id');

        $table->string('Name');

        $table->text('Description')->nullable();

        $table->boolean('IsActive')->default(true);

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();
    });
}

public function down(): void
{
    Schema::dropIfExists('TicketCategories');
}
};
