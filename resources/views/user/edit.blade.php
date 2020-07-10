@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Hobby bearbeiten</div>

                <div class="card-body">
                    <form autocomplete="off" action="/user/{{ $user->id}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="motto">Motto</label>
                            <input type="text"
                                class="form-control {{ $errors->has('motto') ? 'border-danger' : '' }}"
                                id="motto" name="motto" value="{{ old('motto') ?? $user->motto }}">
                            <small class="form-text text-danger">{!! $errors->first('motto') !!}</small>
                        </div>
                        <div class="mb-2">
                            @if (file_exists("img/user/" . auth()->id() . "_gross.jpg"))
                            <img style="max-width: 400px; max-height: 300px;" src="/img/user/{{ auth()->id() }}_gross.jpg" alt="thumb">
                            <div class="float-right">
                                <a class="btn btn-sm btn-outline-danger" href="/delete-image/user/{{ auth()->id() }}">Bild löschen</a>
                            </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="bild">Bild</label>
                            <input type="file"
                                class="form-control {{ $errors->has('bild') ? 'border-danger' : '' }}"
                                id="bild" name="bild" value="">
                            <small class="form-text text-danger">{!! $errors->first('bild') !!}</small>
                        </div>
                        <div class="form-group">
                            <label for="ueber_mich">Über mich</label>
                            <textarea
                                class="form-control"
                                id="ueber_mich" name="ueber_mich"
                                rows="5">{{ old('ueber_mich') ?? $user->ueber_mich }}</textarea>
                        </div>
                        <input class="btn btn-primary mt-4" type="submit" value="absenden">
                    </form>

                    <a class="btn btn-primary btn-sm mt-3 float-right" href="/home"><i
                            class="fas fa-arrow-circle-up"></i> Zurück</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

