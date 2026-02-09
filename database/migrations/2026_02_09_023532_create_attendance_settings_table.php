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
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('attendance_settings')->insert([
            [
                'key' => 'office_latitude',
                'value' => '-6.200000',
                'type' => 'string',
                'description' => 'Office latitude coordinate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'office_longitude',
                'value' => '106.816666',
                'type' => 'string',
                'description' => 'Office longitude coordinate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'allowed_radius_meters',
                'value' => '500',
                'type' => 'integer',
                'description' => 'Allowed radius from office in meters',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'work_start_time',
                'value' => '08:00',
                'type' => 'string',
                'description' => 'Work start time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'work_end_time',
                'value' => '17:00',
                'type' => 'string',
                'description' => 'Work end time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
