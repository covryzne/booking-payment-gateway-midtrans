<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE bookings
        MODIFY status ENUM('pending','lunas','gagal')
        DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE bookings
        MODIFY status ENUM('proses','lunas')");
    }
};
