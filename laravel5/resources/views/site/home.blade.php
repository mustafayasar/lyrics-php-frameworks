@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-md-10 mx-auto">
        @foreach ($songs as $song)
            <div class="post-preview">
                <?php $song_url = route('song_view', [$song->singer->slug, $song->slug]) ?>

                <a href="{{ $song_url }}" title="{{ $song->title }} Lyrics - {{ $song->singer->name }}">
                    <h2 class="post-title">
                        {{ $song->title }}
                    </h2>
                </a>
                <p>
                    {!! SiteHelper::getPreviewLyrics($song->lyrics) !!}

                    <a class="more" href="{{ $song_url }}" title="{{ $song->title }} Lyrics - {{ $song->singer->name }}">Read More <i class="fas fa-angle-double-right"></i></a>
                </p>
                <p class="post-meta">Posted on {{ SiteHelper::getPostedDate($song->created_at) }}</p>
            </div>
        @endforeach
    </div>
@endsection