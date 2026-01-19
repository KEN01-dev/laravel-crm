<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // 担当者（owner）
            $table->foreignId('owner_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 基本情報
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // 業務用ステータス
            $table->string('status')->default('active'); 
            // active / prospect / inactive

            $table->text('notes')->nullable();

            $table->timestamps();

            // 実務っぽさを出すための index
            $table->index(['owner_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
