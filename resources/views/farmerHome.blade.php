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
                                <div style="float:left" onclick="location.href='/fieldPhases/{{$field['fieldName']}}';" class="btn btn-info"> {{ $field['fieldName'] }}</div>
                                <form  method="POST" action="/api/deletefield" >
                                    <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                    <input style="float:right" class="btn btn-danger" type="submit" value="Delete">
                                </form>
                            </div>
                        </div>
                        <br>
                    @endforeach

                    <br><br><br><br>
                    <hr>
                    
                    </br> 
                    <div style="float:left;" onclick="location.href='createField';" class="btn-info btn">{{ trans('farmerhome.newfield') }}</div>

                    <div  style="float:left;" aria-label="Left Align">
                         <span style="position:relative;top: 5px;left:5px;" onmouseover="showInfo()" onmouseout="hideInfo()" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    </div>
                    <div style="display:none;float:left;margin-left:10px;" id='information-box'>
                        {{ trans('farmerhome.info') }}                         
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
