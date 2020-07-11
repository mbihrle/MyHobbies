<?php

namespace App\Http\Controllers;

use App\Hobby;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Gate;


class HobbyController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
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

        if ($request->bild) {
            $this->saveImages($request->bild, $hobby->id);
        }

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
        if (auth()->guest()) {
            abort(403);
        }

        abort_unless($hobby->user_id === auth()->id() || auth()->user()->rolle==='admin', 403);

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

        if (auth()->guest()) {
            abort(403);
        }

        abort_unless(Gate::allows('update', $hobby), 403);


        $request->validate(
            [
                'name' => 'required|min:3',
                'beschreibung' => 'required|min:5',
                'bild' => 'mimes:jpg,jpeg,bmp,png,gif'
            ]
            );

            if ($request->bild) {
                $this->saveImages($request->bild, $hobby->id);
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

        if (auth()->guest()) {
            abort(403);
        }

        abort_unless(Gate::allows('delete', $hobby), 403);


        $old_name = $hobby->name;
        $hobby->delete();
        return back()->with([
            'meldung_success' => 'Das Hobby <b>'. $old_name . '</b> wurde gelöscht.'
            ]);
    }

    public function saveImages($bildInput, $hobby_id) {
        $bild = Image::make($bildInput);
        $breite = $bild->width();
        $hoehe = $bild->height();
        if ($breite > $hoehe) {
            //Querformat
            Image::make($bildInput)
                ->widen(1200)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_gross.jpg')
                ->widen(400)->pixelate(12)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_verpixelt.jpg');
            Image::make($bildInput)
                ->widen(60)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_thumb.jpg');
        } else {
            //Hochformat
            Image::make($bildInput)
                ->heighten(900)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_gross.jpg')
                ->heighten(400)->pixelate(12)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_verpixelt.jpg');
            Image::make($bildInput)
                ->heighten(60)
                ->save(public_path() . '/img/hobby/' . $hobby_id . '_thumb.jpg');
        }
    }

    public function deleteImages($hobby_id) {
        if (file_exists(public_path() . '/img/hobby/' . $hobby_id . '_thumb.jpg'))
            unlink(public_path() . '/img/hobby/' . $hobby_id . '_thumb.jpg');
        if (file_exists(public_path() . '/img/hobby/' . $hobby_id . '_gross.jpg'))
            unlink(public_path() . '/img/hobby/' . $hobby_id . '_gross.jpg');
        if (file_exists(public_path() . '/img/hobby/' . $hobby_id . '_verpixelt.jpg'))
            unlink(public_path() . '/img/hobby/' . $hobby_id . '_verpixelt.jpg');
        return back();
    }
}
