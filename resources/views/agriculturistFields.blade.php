@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">GeoServer</div>

                    <div class="panel-body">
                        @foreach($fields as $field)
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="float:left" onclick="location.href='/agiculturistFieldPhases/{{$id}}/{{$field['fieldName']}}';" class="btn btn-info"> {{ $field['fieldName'] }}</div>
                                </div>
                            </div>
                            <br>
                        @endforeach
                            <div style="float:left" onclick="goBack()" class="btn-info btn">{{ trans('agricalturistfields.back') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
