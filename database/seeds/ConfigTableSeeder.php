<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('config')->insert([
            [
                'label' => 'Максимальный размер сообщения (символов)',
                'value' => 100,
                'rules' => 'required|integer|max:3',
            ],
            [
                'label' => 'Время выборки (последние N секунд)',
                'value' => 300,
                'rules' => 'required|integer|max:5',
            ],
        ]);
    }
}
