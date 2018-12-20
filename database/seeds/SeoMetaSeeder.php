<?php

use Illuminate\Database\Seeder;
use \App\Modules\SeoAgent\Models\MetaSchemaEloquent;

class SeoMetaSeeder extends Seeder
{
    use \Illuminate\Foundation\Testing\WithFaker;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $faker = \Faker\Factory::create();

        $defaultUser = new \App\User();
        $defaultUser->name = 'ryandadeng@gmail.com';
        $defaultUser->email = 'ryandadeng@gmail.com';
        $defaultUser->password = \Illuminate\Support\Facades\Hash::make('123123123');
        $defaultUser->save();
        for ($a = 0; $a < 5; $a++) {
            $currentData = new MetaSchemaEloquent();
            $currentData->fill([
                'description' => $faker->sentence(80),
                'title' => $faker->title,
                'keywords' => $faker->words,
                'canonical' => $faker->url
            ]);

            $num = rand(1, 2);
            $url = $faker->url;
            $obj = new \App\Modules\SeoAgent\Models\SeoAgentDraftData();
            $obj->path = $url;
            $obj->hash = md5($url);
            $obj->current_data = $currentData->toArray();

            if ($num % 2 === 0) {
                $draft = (new MetaSchemaEloquent())->fill([
                    'description' => $faker->sentence(80),
                    'title' => $faker->title,
                    'keywords' => $faker->words,
                    'canonical' => $faker->url
                ])->toArray();
                $obj->draft_data = $draft;
                $obj->last_approved_at = $faker->dateTime;
                $obj->draft_at = $faker->dateTime;
            } else {
                $draft = (new MetaSchemaEloquent())->fill([
                    'description' => '',
                    'title' => '',
                    'keywords' => [],
                    'canonical' => $faker->url
                ])->toArray();
                $obj->draft_data = $draft;
            }


            if ($obj->last_approved_at && $obj->last_approved_at > $obj->draft_at) {
                $obj->type = 1;
            }

            if ($obj->draft_at && $obj->last_approved_at < $obj->draft_at) {
                $obj->type = 2;
            }

            $obj->save();
        }

    }
}
