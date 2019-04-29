@extends('admin.layouts.app')

@section('content')
    <div class="col-md-12">
        <h1 style="margin-top: 6px; margin-bottom: 20px;">Song List</h1>

        <div style="margin-top: -35px;">
            <a href="{{ route('song.create') }}" class="btn btn-primary pull-right">Create A Song</a>
        </div>

        <div class="clearfix"></div>

        {!!$grid->make()!!}
    </div>
@endsection
