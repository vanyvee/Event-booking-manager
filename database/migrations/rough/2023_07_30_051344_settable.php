<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $currentDB = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            DB::statement('ALTER TABLE'.$table->{"Tables_in_".$currentDB}.'ENGINE=InnoDB');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $currentDB = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            DB::statement('ALTER TABLE'.$table->{"Tables_in_".$currentDB}.'ENGINE=MyISAM');
        }
    }
};
