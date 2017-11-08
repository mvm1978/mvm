<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\Library\GenresModel;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->delete();

        GenresModel::create([
            'genre' => 'Action and Adventure',
        ]);

        GenresModel::create([
            'genre' => 'Anthology',
        ]);

        GenresModel::create([
            'genre' => 'Art',
        ]);

        GenresModel::create([
            'genre' => 'Autobiographies',
        ]);

        GenresModel::create([
            'genre' => 'Biographies',
        ]);

        GenresModel::create([
            'genre' => 'Children\'s',
        ]);

        GenresModel::create([
            'genre' => 'Comics',
        ]);

        GenresModel::create([
            'genre' => 'Cookbooks',
        ]);

        GenresModel::create([
            'genre' => 'Diaries',
        ]);

        GenresModel::create([
            'genre' => 'Dictionaries',
        ]);

        GenresModel::create([
            'genre' => 'Drama',
        ]);

        GenresModel::create([
            'genre' => 'Encyclopedias',
        ]);

        GenresModel::create([
            'genre' => 'Fantasy',
        ]);

        GenresModel::create([
            'genre' => 'Guide',
        ]);

        GenresModel::create([
            'genre' => 'Health',
        ]);

        GenresModel::create([
            'genre' => 'History',
        ]);

        GenresModel::create([
            'genre' => 'Horror',
        ]);

        GenresModel::create([
            'genre' => 'Journals',
        ]);

        GenresModel::create([
            'genre' => 'Math',
        ]);

        GenresModel::create([
            'genre' => 'Mystery',
        ]);

        GenresModel::create([
            'genre' => 'Poetry',
        ]);

        GenresModel::create([
            'genre' => 'Prayer books',
        ]);

        GenresModel::create([
            'genre' => 'Religion, Spirituality & New Age',
        ]);

        GenresModel::create([
            'genre' => 'Romance',
        ]);

        GenresModel::create([
            'genre' => 'Satire',
        ]);

        GenresModel::create([
            'genre' => 'Science',
        ]);

        GenresModel::create([
            'genre' => 'Science fiction',
        ]);

        GenresModel::create([
            'genre' => 'Self help',
        ]);

        GenresModel::create([
            'genre' => 'Travel',
        ]);
    }
}
