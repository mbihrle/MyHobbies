<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class hobbyTagController extends Controller
{
    public function getFilteredHobbies($tag_id) {
        // echo "filtern nach tag id" . $tag_id;
        $tag = new Tag();
        $filterTag = $tag::findOrFail($tag_id);
        $filteredHobbies = $filterTag->filteredHobbies()->paginate(10);

        return view('hobby.filteredByTag')->with(
            [
                'hobbies' => $filteredHobbies,
                'tag' => $filterTag
            ]
        );

    }
}
