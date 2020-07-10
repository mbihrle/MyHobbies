<?php

namespace App\Http\Controllers;

use App\User;
use App\Hobby;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // $hobbies = Hobby::where('user_id', $user->id)
            // ->orderBy('updated_at', 'desc')
            // ->get();
        return view('user.show')->with(
            [
                'user' =>  $user
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'motto' => 'required|min:3',
                'bild' => 'mimes:jpg,jpeg,bmp,png,gif'
            ]
            );

            if ($request->bild) {
                $this->saveImages($request->bild, $user->id);
            }

            $user->update([
                'motto' => $request->motto,
                'ueber_mich' => $request->ueber_mich
            ]);
        // return $this->index()->with([
        //     'meldung_success' => 'Der User <b>'. $request->name . '</b> wurde bearbeitet.'
        //     ]);
        // return redirect('/user/' . $user->id)->with([
        //     'meldung_success' => 'Der User <b>'. $request->name . '</b> wurde bearbeitet.'
        //     ]);
        return redirect('/home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    public function saveImages($bildInput, $user_id) {
        $bild = Image::make($bildInput);
        $breite = $bild->width();
        $hoehe = $bild->height();
        if ($breite > $hoehe) {
            //Querformat
            Image::make($bildInput)
                ->widen(500)
                ->save(public_path() . '/img/user/' . $user_id . '_gross.jpg')
                ->widen(400)->pixelate(12)
                ->save(public_path() . '/img/user/' . $user_id . '_verpixelt.jpg');
            Image::make($bildInput)
                ->widen(60)
                ->save(public_path() . '/img/user/' . $user_id . '_thumb.jpg');
        } else {
            //Hochformat
            Image::make($bildInput)
                ->heighten(500)
                ->save(public_path() . '/img/user/' . $user_id . '_gross.jpg')
                ->heighten(400)->pixelate(12)
                ->save(public_path() . '/img/user/' . $user_id . '_verpixelt.jpg');
            Image::make($bildInput)
                ->heighten(60)
                ->save(public_path() . '/img/user/' . $user_id . '_thumb.jpg');
        }
    }

    public function deleteImages($user_id) {
        if (file_exists(public_path() . '/img/user/' . $user_id . '_thumb.jpg'))
            unlink(public_path() . '/img/user/' . $user_id . '_thumb.jpg');
        if (file_exists(public_path() . '/img/user/' . $user_id . '_gross.jpg'))
            unlink(public_path() . '/img/user/' . $user_id . '_gross.jpg');
        if (file_exists(public_path() . '/img/user/' . $user_id . '_verpixelt.jpg'))
            unlink(public_path() . '/img/user/' . $user_id . '_verpixelt.jpg');
        return back();
    }

}
