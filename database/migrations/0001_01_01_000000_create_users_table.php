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
        $table->string('first_name', 100);
        $table->string('last_name', 100);
        $table->string('email', 254)->unique();
        $table->string('phone', 30)->nullable();
        
        // Laravel usa 'password' por defecto, no 'password_hash'
        $table->string('password');
        
        $table->enum('role', ['SUPER_ADMIN', 'ADMIN', 'ESTETICISTA', 'CLIENTE'])->default('CLIENTE');
        $table->boolean('is_active')->default(true);
        
        // Restricción de MySQL para un solo SUPER_ADMIN
        $table->integer('super_admin_slot')->nullable()->storedAs("CASE WHEN role = 'SUPER_ADMIN' THEN 1 ELSE NULL END");
        $table->unique('super_admin_slot');
        
        $table->index(['role', 'is_active']);
        $table->rememberToken();
        $table->timestamps();
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
