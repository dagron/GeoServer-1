@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @foreach($fields as $field)
                            <div class="row">
                                <div class="col-md-12">
                                    <div  style="float:left" onclick="location.href='/agiculturistShowField/{{$id}}/{{$field['fieldName']}}/{{$field['date']}}';" class="btn btn-info"> {{ $field['date'] }}</div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <br><br>
                        <hr>
                        <div style="float:left" onclick="goBack()" class="btn-info btn">Back</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection