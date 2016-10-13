@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create Phase for: {{$field['fieldName']}}</div>

                    <div class="panel-body">
                        <div class="form-group">

                            <form method="POST" action="/api/uploadfieldDate" enctype="multipart/form-data">
                                <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                <label for="date">Date of Photo</label>
                                <input class="form-control" type="date" name="date"><br>

                                <label for="date">Field Photo</label>
                                <input class="form-control" type="file" name="field_image" accept="image/*"><br><br>
                                <input style="float:right" class="btn btn-info" type="submit" value="Submit">
                            </form>
                            <div style="float:left" onclick="goBack()" class="btn-info btn">Back</div>


                            {{--Iterate Errors--}}
                            <br>
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (isset($imageError))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $imageError }}</li>
                                    </ul>
                                </div>
                            @endif
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
