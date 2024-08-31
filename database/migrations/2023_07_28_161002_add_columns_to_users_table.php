<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->dateTime('last_login_date')->nullable();
            $table->unsignedBigInteger('default_role_id')->default(0);
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('status_id')->default(1);
            
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('last_login_date');
            $table->dropColumn('avatar');
            $table->dropColumn('default_role_id');
            $table->dropColumn('creator_id');
            $table->dropColumn('status_id');
        });
    }
};
