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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable(false);
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('image_name')->nullable();
            $table->string('image_url')->nullable();
            $table->string('publicId')->nullable(false);
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
            $table->rememberToken();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->default(null)->onUpdate(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('deleted_at')->nullable()->default(null)->onUpdate(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
        });
    }
/**
 * id bigint UN AI PK 
username varchar(255) 
phone varchar(255) 
email varchar(255) 
password varchar(255) 
date_of_birth date 
address text 
image_name varchar(255) 
image_url varchar(255) 
role_id bigint UN 
publicId varchar(255) 
created_at timestamp 
updated_at timestamp 
remember_token varchar(100)
 */
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
