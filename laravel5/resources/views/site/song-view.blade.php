@section('title', $song->singer->name.' - '.$song->title. ' Lyrics')

@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-md-10 mx-auto">
        <div class="post-preview">
            <p>
                {!! $song->lyrics !!}
            </p>
            <p class="post-meta">Posted on {{ SiteHelper::getPostedDate($song->created_at) }}</p>
            <p class="post-meta">Viewed {{ $song->hit }} times</p>
        </div>

        <p>
            <a class="btn btn-primary" href="{{ route('singer_songs', $song->singer->slug) }}">{{ $song->singer->name }} All Songs</a>
        </p>
    </div>
@endsection
