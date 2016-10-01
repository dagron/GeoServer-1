@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @foreach($users as $user)
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="float:left" onclick="location.href='/agriculturistFields/{{$user->id}}';" class="btn btn-info">
                                        {{ $user->name }}
                                    </div>
                                    <form  method="POST" action="http://localhost:8000/api/deletefield" >
                                        <input type="hidden" name="fieldName" value="">
                                        <input style="float:right" class="btn btn-danger" type="submit" value="Delete">
                                    </form>
                                </div>
                            </div>
                            <br>
                        @endforeach

                        <br><br><br><br>
                        <hr>
                        <div onclick="location.href='/addFarmer';" class="btn-info btn">Add New Farmer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
