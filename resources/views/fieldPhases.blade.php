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
                                    <div  style="float:left" onclick="location.href='/showField/{{$field['fieldName']}}/{{$field['date']}}';" class="btn btn-info"> {{ $field['date'] }}</div>
                                    <form  method="POST" action="/api/deletefieldDate" >
                                        <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                        <input type="hidden" name="fieldId" value="{{$field['id']}}">
                                      
                                        <input style="float:right" class="btn btn-danger" type="submit" value="Delete">
                                    </form>
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <br><br>
                        <hr>
                            <div style="float:left" onclick="goBack()" class="btn-info btn">Back</div>
                            <div style="float:right" onclick="location.href='/createFieldDate/{{$fields[0]['fieldName']}}';" class="btn-info btn">New Field Date</div>



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
