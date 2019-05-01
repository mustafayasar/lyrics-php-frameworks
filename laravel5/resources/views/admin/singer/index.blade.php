@extends('admin.layouts.app', ['title' => 'Singer List'])

@section('content')
    <div class="col-md-12">
        <h1 style="margin-top: 6px; margin-bottom: 20px;">Singer List</h1>

        <div style="margin-top: -35px;">
            <a href="{{ route('singer.create') }}" class="btn btn-primary pull-right">Create A Singer</a>
        </div>

        <div class="clearfix"></div>
        {!!$grid->make()!!}
    </div>
@endsection
