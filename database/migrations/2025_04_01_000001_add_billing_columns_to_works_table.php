<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reference only. Do NOT run php artisan migrate.
 * Apply changes via raw SQL as documented in the Account Billing plan.
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->boolean('is_billing_done')->default(false)->after('is_vdn');
            $table->timestamp('billing_done_at')->nullable()->after('is_billing_done');
            $table->unsignedBigInteger('billing_done_by')->nullable()->after('billing_done_at');
            $table->string('invoice_number')->nullable()->after('billing_done_by');
            $table->date('invoice_date')->nullable()->after('invoice_number');
            $table->decimal('invoice_amount', 12, 2)->nullable()->after('invoice_date');
            $table->decimal('amount_without_gst', 12, 2)->nullable()->after('invoice_amount');
            $table->decimal('gst_amount', 12, 2)->nullable()->after('amount_without_gst');

            $table->foreign('billing_done_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropForeign(['billing_done_by']);
            $table->dropColumn([
                'is_billing_done',
                'billing_done_at',
                'billing_done_by',
                'invoice_number',
                'invoice_date',
                'invoice_amount',
                'amount_without_gst',
                'gst_amount',
            ]);
        });
    }
};
