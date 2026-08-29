<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            if (!Schema::hasColumn('works', 'reporting_started_at')) {
                $table->timestamp('reporting_started_at')->nullable();
            }
            if (!Schema::hasColumn('works', 'reporting_ended_at')) {
                $table->timestamp('reporting_ended_at')->nullable();
            }
            if (!Schema::hasColumn('works', 'checking_started_at')) {
                $table->timestamp('checking_started_at')->nullable();
            }
            if (!Schema::hasColumn('works', 'checking_ended_at')) {
                $table->timestamp('checking_ended_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn([
                'reporting_started_at',
                'reporting_ended_at',
                'checking_started_at',
                'checking_ended_at'
            ]);
        });
    }
};
