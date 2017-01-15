@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">GeoServer</div>

                    <div class="panel-body">
                        <ul class="nav nav-tabs" style="display:none;" >     
                         @foreach($fields as $key=>$field)
                               <li @if ($key == 0)class="active" @endif ><a href="#tab{{$key}}" data-toggle="tab">Shipping:{{ $key }}</a></li>
                         @endforeach
                        </ul>
                        <div class="tab-content">
                             @foreach($fields as $key=>$field)
                                <div class="tab-pane @if ($key === 0) active @endif " id="tab{{$key}}">
                                    @if ($key < count($fields)-1)
                                        <a style="float:right" class="btn btn-info btnNextImage" >{{ trans('showfield.next') }}</a>
                                    @endif
                                    @if ($key > 0)
                                        <a style="float:left" class="btn btn-info btnPreviousImage">{{trans('showfield.previous') }}</a>
                                    @endif
                                    <br><br>
                                    <div class="row">
                                        <div class="col-md-12">
                                          <img src="{{ $asset_folder}}/{{$field['image']}} " class="img-thumbnail"  width="100%" height="30%"> 
                                        </div>
                                    </div>
                                    <br>
                                    <a download  href="{{ $asset_folder}}/{{$field['image']}}" ><div class="btn btn-info" >{{ trans('general.download')  }}</div></a>
                                    <br> 
                                    <br>
                                    @foreach($field['comments'] as $comment)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>{{ $comment['user']['name'] }}</strong> <span class="text-muted"> created at {{ $comment['created_at'] }}</span>
                                        </div>
                                        <div class="panel-body">
                                            {{ $comment['text'] }}
                                        </div><!-- /panel-body -->
                                    </div><!-- /panel panel-default -->
                                    @endforeach
                                   <!-- Comment Form --!>

                                    <hr>

                                    <h4>Comment</h4>
                                    <form method="POST" action="/api/standard/comments">
                                         <textarea name="comment_text" class="form-control" rows="3"  ></textarea>
                                         <input type="hidden" name="fieldid" value="{{ $field['id'] }}" >
                                            <br>
                                         <input style="float:right" class="btn btn-info"  type="submit" value="{{ trans('general.submit') }}">
                                   </form>
                                </div>
                            
                             @endforeach
<div style="float:left" onclick="goBack()" class="btn-info btn">{{ trans('fieldphases.back') }}</div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
<script>
 $('.btnNextImage').click(function(){
      var activeElement = $('.nav-tabs > .active');
      activeElement.removeClass('active');
      var nextElement = activeElement.next('li')
          nextElement.addClass('active');

      var currentid = activeElement.find('a').attr('href');
      $(currentid).removeClass('active');

      var nextid = nextElement.find('a').attr('href');
      $(nextid).addClass('active');
 });

   $('.btnPreviousImage').click(function(){
         $('.nav-tabs > .active').prev('li').find('a').trigger('click');
      var activeElement = $('.nav-tabs > .active');
      activeElement.removeClass('active');
      var prevElement = activeElement.prev('li')
          prevElement.addClass('active');

      var currentid = activeElement.find('a').attr('href');
      $(currentid).removeClass('active');

      var previd = prevElement.find('a').attr('href');
      $(previd).addClass('active');
 
   });
</script>
   </div>
@endsection
