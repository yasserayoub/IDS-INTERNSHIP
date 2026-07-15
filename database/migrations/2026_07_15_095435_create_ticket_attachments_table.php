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
    Schema::create('TicketAttachments', function (Blueprint $table) {

        $table->id('Id');

        $table->unsignedBigInteger('TicketId');

        $table->unsignedBigInteger('UploadedByUserId');

        $table->string('OriginalFileName');

        $table->string('StoredFileName');

        $table->string('FilePath');

        $table->string('MimeType');

        $table->bigInteger('FileSize');

        $table->timestamp('CreatedAt')->useCurrent();

        $table->timestamp('UpdatedAt')
              ->useCurrent()
              ->useCurrentOnUpdate();

        // Foreign Keys cons`traints

        $table->foreign('TicketId')
              ->references('Id')
              ->on('Tickets')
              ->onDelete('cascade');

        $table->foreign('UploadedByUserId')
              ->references('Id')
              ->on('Users')
              ->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('TicketAttachments');
}
};
