@extends('admin.layouts.app')

@section('content')
    <div class="site-index">

        <div class="jumbotron">
            <h1>Welcome to the Panel!</h1>


        </div>

        <div class="body-content" style="text-align: center;">
            <p style="margin-top: 45px;">
                <a class="btn btn-default" href="{{ route('singer.index') }}">There are <strong>{{ $singers_count }}</strong> singers</a>
                <a class="btn btn-success" href="{{ route('singer.create') }}">Create Singer</a>
            </p>
            <p>
                <a class="btn btn-default" href="{{ route('song.index') }}">There are <strong>{{ $songs_count }}</strong> songs</a>
                <a class="btn btn-success" href="{{ route('song.create') }}">Create Song</a>
            </p>

            {{--<p style="padding-top: 35px;">--}}
            {{--<p>There are {{ $search_items_count }} elastic search items</p>--}}
            {{--<a class="btn btn-primary" href="">Mysql To Elastic</a>--}}
            {{--</p>--}}

            {{--<p style="padding-top: 35px;">--}}
                {{--<a class="btn btn-danger" href="">Flush Redis</a>--}}
            {{--</p>--}}


        </div>
    </div>

@endsection
