@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @foreach($fields as $field)
                        <div  onclick="location.href='fieldPhases/{{$field['fieldName']}}';" class="btn btn-info btn-block"> {{ $field['fieldName'] }}</div>
                    @endforeach

                    <hr>
                    <div onclick="location.href='createField';" class="btn-info btn">New Field</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
