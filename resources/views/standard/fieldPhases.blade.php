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
                                    <div  style="float:left" onclick="location.href='/standard/showField/{{$field['fieldName']}}/{{$field['date']}}';" class="btn btn-info"> {{ $field['date'] }}</div>
                                    <form  method="POST" action="/api/standard/deletefieldDate" >
                                        <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                        <input type="hidden" name="fieldDate" value="{{$field['date']}}">
                                      
                                        <button style="float:right" class="btn btn-danger" type="submit" value="{{ trans('fieldphases.delete') }}">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <br>
                        @endforeach
                        <br><br>
                        <hr>
                            <div style="float:left" onclick="goBack()" class="btn-info btn">{{ trans('fieldphases.back') }}</div>
                            <div style="float:right" onclick="location.href='/standard/createFieldDate/{{$fields[0]['fieldName']}}';" class="btn-info btn">{{ trans('fieldphases.newfielddate') }}</div>

                    <div  style="float:right;" aria-label="Left Align">
                         <span style="position:relative;top: 5px;right:5px;" onmouseover="showInfo()" onmouseout="hideInfo()" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    </div>
                    <div style="display:none;float:right;margin-right:10px;" id='information-box'>
                        {{ trans('fieldphases.info') }}                         
                    </div> 
                   <script>
                        function showInfo() {
                            var infobox = document.getElementById("information-box");
                            infobox.style.display = "block";
                        }

                        function hideInfo() {
                            var infobox = document.getElementById("information-box");
                            infobox.style.display = "none";
                        }
                    </script>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
