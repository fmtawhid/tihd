<?php

namespace Modules\Banner\database\seeders;

use Illuminate\Database\Seeder;

class BannerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('banners')->delete();

        \DB::table('banners')->insert(array (
            0 =>
            array (
                'id' => 1,
                'title' => NULL,
                'file_url' => 'the_daring_player_poster.png',
                'poster_url' => 'the_daring_player_thumb.webp',
                'type' => 'movie',
                'type_id' => '27',
                'type_name' => 'The Daring Player',
                'status' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'created_at' => '2024-10-08 05:18:48',
                'updated_at' => '2024-10-08 05:18:48',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'title' => NULL,
                'file_url' => 'the_smiling_shadows_poster.png',
                'poster_url' => 'the_smiling_shadows_thumb.webp',
                'type' => 'tvshow',
                'type_id' => '1',
                'type_name' => 'The Smiling Shadows',
                'status' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'created_at' => '2024-10-08 05:19:29',
                'updated_at' => '2024-10-08 05:19:29',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'title' => NULL,
                'file_url' => 'the_gunfighters_redemption_poster.png',
                'poster_url' => 'the_gunfighters_redemption_thumb.webp',
                'type' => 'movie',
                'type_id' => '23',
                'type_name' => 'The Gunfighter\'s Redemption',
                'status' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'created_at' => '2024-10-08 05:20:16',
                'updated_at' => '2024-10-08 05:20:16',
                'deleted_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'title' => NULL,
                'file_url' => 'daizys_enchanted_journey_poster.png',
                'poster_url' => 'daizys_enchanted_journey_thumb.webp',
                'type' => 'movie',
                'type_id' => '24',
                'type_name' => 'Daizy\'s Enchanted Journey',
                'status' => 1,
                'created_by' => 2,
                'updated_by' => 2,
                'deleted_by' => NULL,
                'created_at' => '2024-10-08 05:20:53',
                'updated_at' => '2024-10-08 05:20:53',
                'deleted_at' => NULL,
            ),
        ));

        }

    }


