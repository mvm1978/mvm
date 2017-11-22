<?php

use Illuminate\Database\Seeder;

use App\Models\Library\TypeModel;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->delete();

        TypeModel::create([
            'type' => 'Paper',
        ]);

        TypeModel::create([
            'type' => 'Audio',
        ]);
    }
}
