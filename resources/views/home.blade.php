@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                <h2>Hallo {{auth()->user()->name}}</h2>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @isset($hobbies)
                        @if($hobbies->count() > 0)
                            <h5>Deine Hobbies</h5>
                        @endif
                        <ul class="list-group">
                        @foreach($hobbies as $hobby)
                            <li class="list-group-item"><a href="/hobby/{{ $hobby->id }}">{{ $hobby->name }}</a>


                                <a class="ml-2 btn btn-sm btn-outline-primary" href="/hobby/{{ $hobby->id }}/edit"><i class="fas fa-edit"></i>Bearbeiten</a>

                                <form style="display: inline;" action="/hobby/{{ $hobby->id }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <input class="btn btn-outline-danger btn-sm ml-2" type="submit" value="Löschen">
                                    <br>
                                    @foreach($hobby->tags as $tag)
                                        <a class="badge badge-{{ $tag->style }}"href="/hobby/tag/{{ $tag->id }}">{{ $tag->name}}</a>
                                    @endforeach
                                </form>
                                <div class="float-right">{{ $hobby->created_at->diffForHumans() }}</div>
                            </li>
                        @endforeach
                        </ul>
                    @endisset


                    <br>
                    <a class="btn btn-success btn-sm" href="/hobby/create"><i class="fas fa-plus-circle"></i> Neues Hobby anlegen</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
