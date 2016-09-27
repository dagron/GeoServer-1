@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @foreach($fields as $field)
                            <div  onclick="location.href='/showField';" class="btn btn-info btn-block"> {{ $field['date'] }}</div>
                        @endforeach

                        <hr>
                        <div onclick="location.href='createField';" class="btn-info btn">New Field Date</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection