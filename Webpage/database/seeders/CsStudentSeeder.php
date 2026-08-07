<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CsStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = '/www/wwwroot/bots/CS/students.csv';

        if (!file_exists($csvPath)) {
            $this->command->error("CSV file not found at: {$csvPath}");
            return;
        }

        $file = fopen($csvPath, 'r');
        $header = fgetcsv($file); // skip header: Fname,lname,email

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            if (count($row) < 3) {
                continue;
            }

            $firstName = trim($row[0]);
            $lastName = trim($row[1]);
            $email = trim($row[2]);

            if (empty($firstName) || empty($email)) {
                continue;
            }

            // Insert or update
            DB::table('cs_students')->updateOrInsert(
                ['email' => $email],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }

        fclose($file);
        $this->command->info("Seeded {$count} students into cs_students table.");
    }
}
