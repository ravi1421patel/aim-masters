<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->decimal('balance_before', 12, 2)
                ->default(0)
                ->after('amount');

            $table->string('reference_type')
                ->nullable()
                ->after('remarks');

            $table->unsignedBigInteger('reference_id')
                ->nullable()
                ->after('reference_type');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropColumn([
                'balance_before',
                'reference_type',
                'reference_id'
            ]);
        });
    }
};
