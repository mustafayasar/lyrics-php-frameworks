@extends('admin.layouts.app', ['title' => 'Singer Edit'])

@section('content')
    <div class="col-md-5">
        <div class="panel panel-primary">
            <div class="panel-heading">Singer Edit</div>
            <div class="panel-body">
                @include('admin.elements._errors')

                <form method="post" action="{{ route('singer.update', $singer->id) }}">
                    <div class="form-group">
                        @csrf
                        @method('PATCH')

                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{ $singer->name }}" />
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug:</label>
                        <input type="text" class="form-control" name="slug" value="{{ $singer->slug }}" />
                    </div>
                    <div class="form-group">
                        <label for="hit">Hit:</label>
                        <input type="text" class="form-control" name="hit" value="{{ $singer->hit }}" disabled />
                    </div>
                    <div class="form-group">
                        <label for="name">Status:</label>
                        <select name="status" class="form-control">
                            @foreach ($statuses as $key => $val)
                                <option value="{{ $key }}" @if ($key == $singer->status) selected @endif>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
