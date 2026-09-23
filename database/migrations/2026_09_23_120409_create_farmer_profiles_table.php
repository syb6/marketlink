<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stall_name');
            $table->text('bio')->nullable();
            $table->string('contact_person')->nullable();
            $table->json('market_ids')->nullable(); // which markets they sell at
            $table->json('operating_days')->nullable();
            $table->json('pickup_windows')->nullable(); // [{"day":"Saturday","from":"08:00","to":"12:00"}]
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('stall_image')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('suspended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_profiles');
    }
};
