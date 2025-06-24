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
        $courses = DB::table('courses')->whereNotNull('thumbnail')->get();

        foreach ($courses as $course) {
            $thumbnail = $course->thumbnail;
            
            // Fix double-prefixed URLs
            if (strpos($thumbnail, 'http://127.0.0.1:8000/storage/http://127.0.0.1:8000/storage/') === 0) {
                $thumbnail = str_replace(
                    'http://127.0.0.1:8000/storage/http://127.0.0.1:8000/storage/',
                    'storage/',
                    $thumbnail
                );
            }
            
            // Fix single-prefixed URLs
            if (strpos($thumbnail, 'http://127.0.0.1:8000/storage/') === 0) {
                $thumbnail = str_replace(
                    'http://127.0.0.1:8000/storage/',
                    'storage/',
                    $thumbnail
                );
            }
            
            // Update the database
            DB::table('courses')
                ->where('id', $course->id)
                ->update(['thumbnail' => $thumbnail]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a data fix, no need for rollback
    }
}; 