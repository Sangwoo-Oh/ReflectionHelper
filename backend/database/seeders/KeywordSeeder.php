<?php

namespace Database\Seeders;

use App\Models\Keyword;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        Keyword::factory()->count(10)->create([
            'user_id' => $user->id
        ]);
    }
}
