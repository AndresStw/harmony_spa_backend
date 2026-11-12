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
    Schema::create('user_sessions', function (Blueprint $table) {
        $table->id();
        
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('jti', 36)->unique(); // Identificador del token
        $table->timestamp('last_activity_at')->useCurrent();
        $table->timestamp('expires_at');
        $table->timestamp('revoked_at')->nullable();
        $table->boolean('is_active')->default(true);
        $table->string('ip_address', 45)->nullable();
        $table->string('user_agent')->nullable();
        $table->timestamps();
        
        $table->index(['user_id', 'is_active']);
        $table->index('expires_at');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
