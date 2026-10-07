<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kodebukus', function (Blueprint $table) {
            $table->boolean('label_dicetak')->default(false)->after('status');
            $table->timestamp('label_dicetak_at')->nullable()->after('label_dicetak');
        });
    }

    public function down(): void
    {
        Schema::table('kodebukus', function (Blueprint $table) {
            $table->dropColumn(['label_dicetak', 'label_dicetak_at']);
        });
    }
};