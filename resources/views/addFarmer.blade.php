@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ trans('addfarmer.create') }}</div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="fieldName">{{ trans('addfarmer.farmername') }}</label>
                             <div class="input-group">
                            <span class="input-group-addon" aria-hidden="true"><span class="glyphicon glyphicon-search"></span></span>
                
                            <input id="farmerName" class="form-control" onKeyUp="keyUp()" type="text" name="farmName"><br>
                            </div>
                            <br>
                            <div id="usersResult"></div>
                            <div style="float:left" onclick="goBack()" class="btn-info btn">{{ trans('addfarmer.back') }}</div>

                            <script>

                                 function keyUp(){
                                     var name = $('#farmerName').val()

                                     $.ajax({
                                         type: 'GET',
                                         url: "/api/users/"+name,
                                         contentType: 'application/json; charset=utf-8',
                                         dataType: 'json',
                                         success: function(users) {
                                             $('#usersResult').empty();
                                             if(users.length > 0) {
                                                 for (i = 0; i < users.length; i++) {
                                                     $('#usersResult').append(
                                                            '<form method="POST" action="/api/users/addFarmer">' +
                                                            '<input type="hidden" name="farmerId" value="'+users[i].id+'">' +
                                                            '<input type="submit" class="btn btn-default btn-block" style="text-align: left" value="'+users[i].name+ '">' +
                                                            "<div>" +
                                                            "</form>"
                                                     )
                                                 }
                                                 $('#usersResult').append("<br>");
                                             } else {
                                                 $('#usersResult').append(
                                                         "No user Found" + "<br>"
                                                 )
                                             }
                                         },
                                         error: function() {
                                             console.log('no text');
                                         }
                                     });
                                }
                            </script>




                            {{--Iterate Errors--}}
                            <br>

                            @if (isset($nameExists))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $nameExists }}</li>
                                    </ul>
                                </div>
                            @endif

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
