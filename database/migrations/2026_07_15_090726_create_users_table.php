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
        Schema::create('Users', function (Blueprint $table) {


            $table->id('Id');

            $table->unsignedBigInteger('RoleId'); //this is teh colum name 

            $table->string('Name', 100);
            $table->string('Email', 255)->unique();
            $table->string('Password', 255);
            $table->string('Phone', 15)->nullable()->unique();
            $table->string('Department', 100);
            $table->boolean('IsActive')->default(true);
            $table->string('RememberToken', 100)->nullable();


            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent();

            // Foreign Key Constraint
            $table->foreign('RoleId')
                  ->references('Id')
                  ->on('Roles')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Users');
    }
};
