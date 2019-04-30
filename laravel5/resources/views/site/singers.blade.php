@section('title', $title)

@extends('layouts.app')

@section('content')
    <div class="col-lg-8 col-md-10 mx-auto">
        <div>
            <ul class="letters">
                @foreach ($letters as $key => $val)
                    <li><a href="{{ route('singers', $key) }}">{{ $val }}</a></li>
                @endforeach
            </ul>
        </div>
        <ul class="list-group">
            @if (count($singers) > 0)
                @foreach ($singers as $singer)
                <li class="list-group-item">
                    <a href="{{ route('singer_songs', $singer->slug) }}"
                       title="{{ $singer->name }} Songs">{{ $singer->name }}</a>
                </li>
                @endforeach
            @else
            <p class="text-danger">There is no singer.</p>
            @endif
        </ul>
        <div>
            {{ $singers->links() }}
        </div>
    </div>
@endsection
