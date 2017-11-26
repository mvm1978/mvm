<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\Library\GenreModel;

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

        GenreModel::create([
            'genre' => 'Action and Adventure',
        ]);

        GenreModel::create([
            'genre' => 'Anthology',
        ]);

        GenreModel::create([
            'genre' => 'Art',
        ]);

        GenreModel::create([
            'genre' => 'Autobiographies',
        ]);

        GenreModel::create([
            'genre' => 'Biographies',
        ]);

        GenreModel::create([
            'genre' => 'Children\'s',
        ]);

        GenreModel::create([
            'genre' => 'Comics',
        ]);

        GenreModel::create([
            'genre' => 'Cookbooks',
        ]);

        GenreModel::create([
            'genre' => 'Diaries',
        ]);

        GenreModel::create([
            'genre' => 'Dictionaries',
        ]);

        GenreModel::create([
            'genre' => 'Drama',
        ]);

        GenreModel::create([
            'genre' => 'Encyclopedias',
        ]);

        GenreModel::create([
            'genre' => 'Fantasy',
        ]);

        GenreModel::create([
            'genre' => 'Guide',
        ]);

        GenreModel::create([
            'genre' => 'Health',
        ]);

        GenreModel::create([
            'genre' => 'History',
        ]);

        GenreModel::create([
            'genre' => 'Horror',
        ]);

        GenreModel::create([
            'genre' => 'Journals',
        ]);

        GenreModel::create([
            'genre' => 'Math',
        ]);

        GenreModel::create([
            'genre' => 'Mystery',
        ]);

        GenreModel::create([
            'genre' => 'Poetry',
        ]);

        GenreModel::create([
            'genre' => 'Prayer books',
        ]);

        GenreModel::create([
            'genre' => 'Religion, Spirituality & New Age',
        ]);

        GenreModel::create([
            'genre' => 'Romance',
        ]);

        GenreModel::create([
            'genre' => 'Satire',
        ]);

        GenreModel::create([
            'genre' => 'Science',
        ]);

        GenreModel::create([
            'genre' => 'Science fiction',
        ]);

        GenreModel::create([
            'genre' => 'Self help',
        ]);

        GenreModel::create([
            'genre' => 'Travel',
        ]);
    }
}
