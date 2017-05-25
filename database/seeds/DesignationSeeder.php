<?php

use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('designations')->insert([
            [
                'name' => 'Where needed most',
                'email' => null
            ],
            [
                'name' => 'TheDoor',
                'email' => null
            ],
            [
                'name' => 'Eminent Worship',
                'email' => 'ian@eminentworship.org',
            ],
            [
                'name' => 'Staff Support',
                'email' => null
            ],
            [
                'name' => 'Mission Trip Support',
                'email' => null
            ],
            [
                'name' => 'REDEEM',
                'email' => null
            ],
        ]);
    }
}
