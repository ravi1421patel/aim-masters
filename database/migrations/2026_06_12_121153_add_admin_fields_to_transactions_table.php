<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_proof')->nullable()->after('status');
            $table->text('admin_remark')->nullable()->after('payment_proof');
            $table->timestamp('approved_at')->nullable()->after('admin_remark');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_proof',
                'admin_remark',
                'approved_at',
            ]);
        });
    }
};
