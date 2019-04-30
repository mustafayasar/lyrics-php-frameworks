@section('title', $title)

@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-md-10 mx-auto">
        <div>
            <ul class="letters">
                @foreach ($letters as $key => $val)
                    <li><a href="{{ route('songs', $key) }}">{{ $val }}</a></li>
                @endforeach
            </ul>
        </div>
        @if (count($songs) > 0)
            <ul class="list-group">
                @foreach ($songs as $song)
                    <li class="list-group-item">
                        <a href="{{ route('song_view', [$song->singer->slug, $song->slug]) }}"
                           title="{{ $song->title }} Lyrics - {{ $song->singer->name }}">{{ $song->title }} - {{ $song->singer->name }}</a>
                    </li>
                @endforeach
            </ul>
            <div>
                {{ $songs->links() }}
            </div>
        @else
            <p class="text-danger">There is no song.</p>
        @endif
    </div>
@endsection
