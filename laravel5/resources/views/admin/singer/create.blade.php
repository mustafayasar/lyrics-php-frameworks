@extends('admin.layouts.app', ['title' => 'Singer Create'])

@section('content')
    <div class="col-md-5">
        <div class="panel panel-primary">
            <div class="panel-heading">Singer Create</div>
            <div class="panel-body">
                @include('admin.elements._errors')

                <form method="post" action="{{ route('singer.store') }}">
                    <div class="form-group">
                        @csrf

                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" />
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
