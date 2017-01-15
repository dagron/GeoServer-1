@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">GeoServer</div>

                 <div class="panel-body" >
                    <div class="btn-group">
                        <div  onclick="location.href='home';" class="btn btn-info" >{{trans('farmerhome.pro') }}</div>
                        <div  onclick="location.href='home-standard';" class="btn btn-default "  disabled="disabled" >{{trans('farmerhome.standard') }}</div>
                     
                    </div>
                    <div  style="float:left;" aria-label="Left Align">
                         <span style="position:relative;top: 5px;right:5px;" onmouseover="showModeInfo()" onmouseout="hideModeInfo()" class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                    </div>
                    <div style="display:none;float:left;margin-left:10px;" id='information-mode-box'>
                        {{ trans('farmerhome.mode') }}                         
                    </div> 
                    <br><br>
                 <div class="input-group">
                        <span class="input-group-addon" aria-hidden="true"><span class="glyphicon glyphicon-search"></span></span>
                         <input type="text" id="fieldName" onkeyup="keyUp()" class="form-control">
                     </div>
                <br>
                <div id="fieldsResult">
                    @foreach($fields as $field)
                        <div class="row">
                            <div class="col-md-12">
                                <div style="float:left" onclick="location.href='/standard/fieldPhases/{{$field['fieldName']}}';" class="btn btn-info"> {{ $field['fieldName'] }}</div>
                                <form  method="POST" action="/api/standard/deletefield" >
                                    <input type="hidden" name="fieldName" value="{{$field['fieldName']}}">
                                    <input style="float:right" class="btn btn-danger" type="submit" value="{{ trans('farmerhome.delete')}}">
                                </form>
                            </div>
                        </div>
                        <br>
                        @endforeach
                </div>

                    <br><br><br><br>
                    <hr>
                    
                    </br> 
                    <div style="float:left;" onclick="location.href='standard/createField';" class="btn-info btn">{{ trans('farmerhome.newfield') }}</div>

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
                         function showModeInfo() {
                            var infobox = document.getElementById("information-mode-box");
                            infobox.style.display = "block";
                        }

                        function hideModeInfo() {
                            var infobox = document.getElementById("information-mode-box");
                            infobox.style.display = "none";
                        }
                        
                        function showInfo() {
                            var infobox = document.getElementById("information-box");
                            infobox.style.display = "block";
                        }

                        function hideInfo() {
                            var infobox = document.getElementById("information-box");
                            infobox.style.display = "none";
                        }
                    </script>
                     <script>

                                 function keyUp(){
                                     var name = $('#fieldName').val()

                                     $.ajax({
                                         type: 'GET',
                                         url: "/api/standard/fields/"+name,
                                         contentType: 'application/json; charset=utf-8',
                                         dataType: 'json',
                                         success: function(fields) {
                                             $('#fieldsResult').empty();
                                             if(fields.length > 0) {
                                                 for (i = 0; i < fields.length; i++) {
                                                     $('#fieldsResult').append(
                                                             '<div class="row">'+
                                                                 '<div class="col-md-12">'+
                                                                     '<div style="float:left" onclick="location.href=\'/fieldPhases/'+fields[i].fieldName+'\';" class="btn btn-info">'+fields[i].fieldName+'</div>'+
                                                                      '<form  method="POST" action="/api/standard/deletefield" >'+
                                                                      '<input type="hidden" name="fieldName" value="'+fields[i].fieldName+'">'+
                                                                      '<input style="float:right" class="btn btn-danger" type="submit" value=" {{ trans('general.delete') }} ">'+
                                                                      '</form>'+
                                                                  '</div>'+
                                                               '</div>'+
                                                               '<br>'
                                                     )
                                                 }
                                                 $('#fieldsResult').append("<br>");
                                             } else {
                                                 $('#fieldsResult').append(
                                                         "No field Found" + "<br>"
                                                 )
                                             }
                                         },
                                         error: function() {
                                             console.log('no text');
                                         }
                                     });
                                }
                            </script>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
