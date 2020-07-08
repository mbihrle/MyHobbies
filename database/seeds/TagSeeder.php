<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $tags =  [
           'Sport' => 'primary',
            'Entspannung' => 'secondary',
            'Fun' => 'warning',
            'Natur' => 'success',
            'Inspiration' => 'light',
            'Freunde' => 'info',
            'Liebe' => 'danger',
            'Interesse' => 'dark'
       ];

       foreach ($tags as $key => $value) {
           $tag = new Tag(
               [
                   'name' => $key,
                    'style' => $value
               ]
            );
            $tag->save();
       }

    }
}
