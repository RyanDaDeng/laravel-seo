<?php

use Illuminate\Database\Seeder;

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

        for ($a = 0; $a < 3000; $a++) {
            $currentData = new \App\Modules\SeoAgent\Objects\Meta();
            $currentData->setCanonical($faker->url);
            $currentData->setDescription($faker->name(100));
            $currentData->setTitle($faker->title);

            $previewData = new \App\Modules\SeoAgent\Objects\Meta();
            $previewData->setCanonical($faker->url);
            $previewData->setDescription($faker->name(100));
            $previewData->setTitle($faker->title);

            $url = $faker->url;
            $obj = new \App\Modules\SeoAgent\Models\SeoMeta();
            $obj->path = $url;
            $obj->hash = md5($url);
            $obj->current_data = $currentData->__toArray();
            $obj->draft_data = $previewData->__toArray();
            $obj->save();
        }

    }
}
