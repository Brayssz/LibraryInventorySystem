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
            $table->unsignedBigInteger('book_id')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('set null');
            $table->foreign('school_id')->references('school_id')->on('schools')->onDelete('set null');
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->enum('transaction_type', ['received', 'lost']);
            $table->integer('quantity');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('reference_number')->unique()->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });

        // Schema::create('division_inventory', function (Blueprint $table) {
        //     $table->id('div_inventory_id');
        //     $table->unsignedBigInteger('book_id')->nullable();
        //     $table->integer('quantity');
        //     $table->timestamps();

        //     $table->foreign('book_id')->references('book_id')->on('books')->onDelete('set null');
        // });

        // Schema::create('division_transactions', function (Blueprint $table) {
        //     $table->id('div_transaction_id');
        //     $table->unsignedBigInteger('div_inventory_id')->nullable();
        //     $table->enum('transaction_type', ['delivered', 'lost', 'sent']);
        //     $table->unsignedBigInteger('sent_to')->nullable();
        //     $table->integer('quantity');
        //     $table->unsignedBigInteger('approved_by')->nullable();
        //     $table->string('reference_number')->unique()->nullable();
        //     $table->date('date')->nullable();
        //     $table->time('time')->nullable();
        //     $table->timestamps();

        //     $table->foreign('div_inventory_id')->references('div_inventory_id')->on('division_inventory')->onDelete('set null');
        //     $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        //     $table->foreign('sent_to')->references('school_id')->on('schools')->onDelete('set null');
        // });

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
