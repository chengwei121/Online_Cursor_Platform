<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            InstructorSeeder::class,
            CourseSeeder::class,
            LessonSeeder::class,
            AssignmentSeeder::class,
            EnrollmentSeeder::class,
            LessonProgressSeeder::class,
            FixCourseImagesSeeder::class,
        ]);
    }
}
