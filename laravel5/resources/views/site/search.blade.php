@section('title', 'Search for "'.$q.'"')

@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-md-10 mx-auto">
        @if (count($result) > 0)
            @foreach($result as $item)
                @if ($item['_source']['type'] == 'song')
                    <div class="post-preview">
                        <a href="{{ $item['_source']['url'] }}" title="{{ $item['_source']['title'] }}">
                            <h2 class="post-title">
                                {{ $item['_source']['title'] }}
                            </h2>
                        </a>
                        <p>
                            {!! SiteHelper::getPreviewLyrics($item['_source']['content']) !!}

                            <a class="more" href="{{ $item['_source']['url'] }}" title="{{ $item['_source']['title'] }}">Read More <i class="fas fa-angle-double-right"></i></a>
                        </p>
                    </div>
                @elseif ($item['_source']['type'] == 'singer')
                    <div class="post-preview">
                        <a href="{{ $item['_source']['url'] }}" title="{{ $item['_source']['title'] }}">
                            <h2 class="post-title">
                                {{ $item['_source']['title'] }} (Singer)
                            </h2>
                        </a>
                    </div>
                @endif
            @endforeach
        @else
            <p class="text-danger">There is no result.</p>
        @endif
    </div>
@endsection