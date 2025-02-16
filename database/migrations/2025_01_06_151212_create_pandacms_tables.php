<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // change laravel default columns
            // make name column unique,we need use name as login field
            $table->string('name')->unique()->change();
            // make email column nullable
            $table->string('email')->nullable()->change();

            // new columns
            $table->string('display_name')->nullable();
            // add avatar column
            $table->string('avatar')->nullable();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // recovery laravel default columns
            $table->string('name')->unique(false)->change();
            $table->string('email')->nullable(false)->change();
            // down new columns
            $table->dropColumn('display_name');
            $table->dropColumn('avatar');
            $table->dropSoftDeletes();
        });
    }
};
