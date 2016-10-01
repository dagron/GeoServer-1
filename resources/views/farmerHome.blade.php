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
                                <div style="float:left" onclick="location.href='/fieldPhases/{{$field['fieldName']}}';" class="btn btn-info"> {{ $field['fieldName'] }}</div>
                                <form  method="POST" action="http://localhost:8000/api/deletefield" >
                                    <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                    <input style="float:right" class="btn btn-danger" type="submit" value="Delete">
                                </form>
                            </div>
                        </div>
                        <br>
                    @endforeach

                    <br><br><br><br>
                    <hr>
                    <div onclick="location.href='createField';" class="btn-info btn">New Field</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
