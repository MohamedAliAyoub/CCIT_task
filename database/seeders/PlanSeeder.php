<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::query()->insert([
            [
                "name" => "monthly",
                "days" => "30",
                "price" => "1.99",
                "price_id" => "price_1LdoKKFjTwbCaerr949o3WeG",
            ], [
                "name" => "yearly",
                "days" => "365",
                "price" => "19.99",
                "price_id" => "price_1LdnULFjTwbCaerrkt2oyOEi",
            ]
        ]);
    }
}
