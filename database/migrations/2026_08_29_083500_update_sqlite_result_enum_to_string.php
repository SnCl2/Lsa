<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // By changing 'result' to string, we remove the SQLite check constraints in the testing database.
        // This allows test runs using SQLite to save 'Positive' / 'Negative' result values.
        Schema::table('works', function (Blueprint $table) {
            $table->string('result')->nullable()->change();
        });
    }

    public function down()
    {
        // No-op
    }
};
