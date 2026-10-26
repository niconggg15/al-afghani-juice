<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'cashier', 'customer', 'analyst') NOT NULL DEFAULT 'customer'");
    }

    public function down()
    {
        DB::table('users')->where('role', 'analyst')->update(['role' => 'customer']);

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'cashier', 'customer') NOT NULL DEFAULT 'customer'");
    }
};
