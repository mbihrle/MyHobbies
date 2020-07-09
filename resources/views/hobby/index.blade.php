@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Alle Hobbies</div>

                <div class="card-body">
                    <ul class="list-group">
                        @foreach($hobbies as $hobby)
                            {{-- Hobby ohne Link --}}
                            {{-- <li class="list-group-item">{{ $hobby->name }} --}}
                            <li class="list-group-item"><a href="/hobby/{{ $hobby->id }}">{{ $hobby->name }}</a>
                            {{-- Extra Link Detailansicht --}}
                            {{-- <a class="ml-2" href="/hobby/{{ $hobby->id }}">Detailansicht</a> --}}

                                <span class="mx-2">Von <a href="/user/{{ $hobby->user->id }}">{{ $hobby->user->name }}</a>({{ $hobby->user->hobbies->count() }} Hobbies)</span>

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
                  @auth
                  <a class="btn btn-success btn-sm mt-3" href="hobby/create"><i class="fas fa-plus-circle"></i> Neues Hobby anlegen</a>
                  @endauth
                  <div class="mt-3">
                    {{$hobbies->links()}}
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

