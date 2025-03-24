<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('status')->default('active'); 
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id('book_id');
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->date('published_date')->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->id('school_id');
            $table->string('name');
            $table->string('address');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('reference_codes', function (Blueprint $table) {
            $table->id('reference_id');
            $table->string('reference_code')->unique();
            $table->timestamps();
        });

        Schema::create('book_requests', function (Blueprint $table) {
            $table->id('request_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('book_id');
            $table->integer('quantity');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('school_id')->references('school_id')->on('schools')->onDelete('cascade');
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('inventory', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('location_id')->nullable(); // Can be school_id or NULL (for division)
            $table->enum('location_type', ['division', 'school']);
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('location_id')->references('school_id')->on('schools')->onDelete('cascade');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('inventory_id');
            $table->integer('quantity');
            $table->enum('transaction_type', ['release', 'delivery', 'receive']);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('transaction_timestamp')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('reference_id')->references('reference_id')->on('reference_codes')->onDelete('set null');
        });

        Schema::create('borrow_transactions', function (Blueprint $table) {
            $table->id('borrow_id');
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id')->nullable(); // Refers to the release transaction
            $table->timestamp('borrow_timestamp');
            $table->timestamp('return_date')->nullable();
            $table->integer('quantity_lost')->default(0);
            $table->enum('status', ['borrowed', 'partially_returned', 'returned'])->default('borrowed');
            $table->timestamps();

            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('transaction_id')->references('transaction_id')->on('transactions')->onDelete('set null');
        });

        Schema::create('return_transactions', function (Blueprint $table) {
            $table->id('return_id');
            $table->unsignedBigInteger('borrow_id');
            $table->integer('quantity');
            $table->timestamp('return_date')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->timestamps();

            $table->foreign('borrow_id')->references('borrow_id')->on('borrow_transactions')->onDelete('cascade');
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('return_transactions');
        Schema::dropIfExists('borrow_transactions');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('book_requests');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('books');
        Schema::dropIfExists('reference_codes');
    }
};
