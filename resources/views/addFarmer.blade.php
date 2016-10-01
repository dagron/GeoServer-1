@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Create</div>

                    <div class="panel-body">
                        <div class="form-group">
                            <label for="fieldName">Farmer Name</label>
                            <input id="farmerName" class="form-control" onKeyUp="keyUp()" type="text" name="farmName"><br>
                            <div id="usersResult"></div>
                            <div style="float:left" onclick="location.href='/home'" class="btn-info btn">Back</div>

                            <script>

                                 function keyUp(){
                                     var name = $('#farmerName').val()

                                     $.ajax({
                                         type: 'GET',
                                         url: "http://localhost:8000/api/users/"+name,
                                         contentType: 'application/json; charset=utf-8',
                                         dataType: 'json',
                                         success: function(users) {
                                             $('#usersResult').empty();
                                             if(users.length > 0) {
                                                 for (i = 0; i < users.length; i++) {
                                                     $('#usersResult').append(
                                                            '<form method="POST" action="http://localhost:8000/api/users/addFarmer">' +
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
                                             alert('Error');
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
