<?php

namespace App\Http\Controllers;

use App\Hobby;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;


class HobbyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $hobbies = Hobby::all();
        // $hobbies = Hobby::paginate(10);


        $meldung_success = Session::get('meldung_success');

        $hobbies = Hobby::orderBy('created_at', 'DESC')->paginate(10);
        return view('hobby.index')->with(
            [
                'hobbies' => $hobbies,
                'meldung_success' => $meldung_success
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hobby.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:3',
                'beschreibung' => 'required|min:5',
                'bild' => 'mimes:jpg,jpeg,bmp,png,gif'
            ]
            );
        $hobby = new Hobby(
            [
                'name' => $request->name,
                'beschreibung' => $request->beschreibung,
                'user_id' => auth()->id()
            ]
        );
        $hobby->save();
        // return redirect('/hobby');

        /*
        return $this->index()->with([
            'meldung_success' => 'Das Hobby <b>'. $hobby->name . '</b> wurde angelegt.'
            ]);
        */

        return redirect('/hobby/' . $hobby->id)->with('meldung_hinweis', 'Bitte weise ein paar Tags zu.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function show(Hobby $hobby)
    {
        $alleTags = Tag::all();  // Alle Tags holen
        $verwendeteTags = $hobby->tags;
        $verfuegbareTags = $alleTags->diff($verwendeteTags);
        $meldung_success = Session::get('meldung_success');
        $meldung_hinweis = Session::get('meldung_hinweis');
        return view('hobby.show')->with(
            [
                'hobby' => $hobby,
                'meldung_success' => $meldung_success,
                'meldung_hinweis' => $meldung_hinweis,
                'verfuegbareTags' => $verfuegbareTags
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function edit(Hobby $hobby)
    {
        return view('hobby.edit')->with('hobby', $hobby);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hobby $hobby)
    {
        $request->validate(
            [
                'name' => 'required|min:3',
                'beschreibung' => 'required|min:5',
                'bild' => 'mimes:jpg,jpeg,bmp,png,gif'
            ]
            );

            if ($request->bild) {
                $bild = Image::make($request->bild);
                $breite = $bild->width();
                $hoehe = $bild->height();
                if ($breite > $hoehe) {
                    //Querformat
                    Image::make($request->bild)
                        ->widen(1200)
                        ->save(public_path() . '/img/hobby/' . $hobby->id . '_gross.jpg');
                    Image::make($request->bild)
                        ->widen(60)
                        ->save(public_path() . '/img/hobby/' . $hobby->id . '_thumb.jpg');
                } else {
                    //Hochformat
                    Image::make($request->bild)
                        ->heighten(900)
                        ->save(public_path() . '/img/hobby/' . $hobby->id . '_gross.jpg');
                    Image::make($request->bild)
                        ->heighten(60)
                        ->save(public_path() . '/img/hobby/' . $hobby->id . '_thumb.jpg');
                }
            }

            $hobby->update([
                'name' => $request->name,
                'beschreibung' => $request->beschreibung
            ]);
        return $this->index()->with([
            'meldung_success' => 'Das Hobby <b>'. $request->name . '</b> wurde bearbeitet.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hobby  $hobby
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hobby $hobby)
    {
        $old_name = $hobby->name;
        $hobby->delete();
        return back()->with([
            'meldung_success' => 'Das Hobby <b>'. $old_name . '</b> wurde gelöscht.'
            ]);
    }
}
