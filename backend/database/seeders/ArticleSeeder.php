<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('articles')->insert([
            [
                'title' => 'Welcome to Mini-CMS',
                'content' => '<p>This is the first article in the system.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laravel Migration Plan',
                'content' => '<p>Migration plan from old backend.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'React Frontend Goals',
                'content' => '<p>Frontend will display articles.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Admin Workflow',
                'content' => '<p>Admins can create/edit/delete.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'SQLite Setup',
                'content' => '<p>Using SQLite database.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Security',
                'content' => '<p>Sanitize user input.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}