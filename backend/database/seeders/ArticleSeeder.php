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
                'title' => 'Start Before You Feel Ready',
                'content' => 'You do not need to feel ready to begin. Most people wait until everything feels perfect, but that moment rarely comes. Progress starts when you take action despite uncertainty. Small steps taken consistently will always beat perfect plans that never begin.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Consistency Beats Motivation',
                'content' => 'Motivation comes and goes, but consistency builds real results. Even on days when you feel unmotivated, showing up and doing a little work keeps momentum alive. Over time, those small efforts compound into meaningful progress.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Focus on What You Can Control',
                'content' => 'There will always be things outside your control, and worrying about them only drains your energy. Instead, focus on what you can do today. Your effort, your attitude, and your actions are always within your control.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Failure Is Part of Growth',
                'content' => 'Every failure teaches something valuable. Instead of avoiding mistakes, learn from them. Growth does not come from staying comfortable, it comes from trying, failing, and improving.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Do Not Compare Your Journey',
                'content' => 'Everyone moves at a different pace. Comparing yourself to others can make you lose sight of your own progress. Focus on becoming better than you were yesterday, not better than someone else.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Small Habits Create Big Changes',
                'content' => 'Big results rarely come from one huge action. They come from small habits repeated over time. Improving just a little every day leads to powerful long-term change.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}