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
    Schema::create('ActivityLogs', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('UserId');

        $table->string('Action');

        $table->string('EntityType');

        $table->unsignedBigInteger('EntityId');

        $table->text('Description')->nullable();

        $table->string('IpAddress')->nullable();

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Key

        $table->foreign('UserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('ActivityLogs');
}
};
