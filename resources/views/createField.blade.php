@extends('layouts.app')

@section('content')
<style>
html,
body {
    width: 100%;
    height: 100%;
    font-size: 16px;
}

#animation-bg {
    position: absolute;
    top: 0;
    left:0;
    width: 100%;
    height: 100%;
    background: rgba(1,1,1,0.5);
    z-index:2;
}

.blob {
    width: 2rem;
    height: 2rem;
    background: green;
    border-radius: 50%;
    position: absolute;
    left: calc(50% - 1rem);
    top: calc(50% - 1rem);
    box-shadow: 0 0 1rem rgba(255, 255, 255, 0.15);
    z-index:3;
}

.blob-2 {
    animation: animate-to-2 1.5s infinite;
}

.blob-3 {
    animation: animate-to-3 1.5s infinite;
}

.blob-1 {
    animation: animate-to-1 1.5s infinite;
}

.blob-4 {
    animation: animate-to-4 1.5s infinite;
}

.blob-0 {
    animation: animate-to-0 1.5s infinite;
}

.blob-5 {
    animation: animate-to-5 1.5s infinite;
}

@keyframes animate-to-2 {
    25%,
            75% {
                    transform: translateX(-1.5rem) scale(0.75);
                        }
    95% {
            transform: translateX(0rem) scale(1);
                }
}

@keyframes animate-to-3 {
    25%,
            75% {
                    transform: translateX(1.5rem) scale(0.75);
                        }
    95% {
            transform: translateX(0rem) scale(1);
                }
}

@keyframes animate-to-1 {
    25% {
            transform: translateX(-1.5rem) scale(0.75);
                }
    50%,
            75% {
                    transform: translateX(-4.5rem) scale(0.6)
                            }
    95% {
            transform: translateX(0rem) scale(1);
                }
}

@keyframes animate-to-4 {
    25% {
        transform: translateX(1.5rem) scale(0.75);
    }
    50%,
    75% {
        transform: translateX(4.5rem) scale(0.6)
    }
    95% {
        transform: translateX(0rem) scale(1);
    }
}

@keyframes animate-to-0 {
    25% {
        transform: translateX(-1.5rem) scale(0.75);
    }
    50% {
        transform: translateX(-4.5rem) scale(0.6)
    }
    75% {
        transform: translateX(-7.5rem) scale(0.5)
    }
    95% {
        transform: translateX(0rem) scale(1);
    }
}

@keyframes animate-to-5 {
    25% {
        transform: translateX(1.5rem) scale(0.75);
    }
    50% {
        transform: translateX(4.5rem) scale(0.6)
    }
    75% {
        transform: translateX(7.5rem) scale(0.5)
    }
    95% {
        transform: translateX(0rem) scale(1);
    }
}
</style>

<div id="animation-bg">
</div>
  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">

        <div class="panel panel-default">

          <div class="panel-heading">{{ trans('createfield.create') }}</div>

          <div class="panel-body">
            <div class="form-group">

            <form method="POST" action="/api/uploadfield" enctype="multipart/form-data">
              <label for="fieldName">{{ trans('createfield.newfieldname') }}</label>
              <input class="form-control" type="text" name="fieldName"><br>

              <label for="date">{{ trans('createfield.dateofphoto') }}</label>
              <input class="form-control" type="date" name="date"><br>

              <label for="date">{{ trans('createfield.fieldphoto') }}</label>
              <input class="form-control" type="file" name="field_image" accept="image/*">
                <br>
                <div class="checkbox">
                     <label><input type="checkbox" name="is_processed" value="is_processed">{{ trans('createfield.alreadyprocessed') }}</label> 
                </div>
                <br><br>
                <input style="float:right" onclick="showAnimation()" class="btn btn-info" type="submit" value="{{ trans('general.submit') }}">
              </form>
              <div style="float:left" onclick="goBack()" class="btn-info btn">{{ trans('createfield.back') }}</div>

              {{--Iterate Errors--}}
              <br>
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
<svg xmlns="http://www.w3.org/2000/svg" version="1.1">
  <defs>
    <filter id="gooey">
      <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
      <feBlend in="SourceGraphic" in2="goo"></feBlend>
    </filter>
  </defs>
</svg>
<div id="animation">
<div class="blob blob-0"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>
<div class="blob blob-4"></div>
<div class="blob blob-5"></div>
</div>
<script>

function hideAnimation() {
var bg = document.getElementById("animation-bg");
bg.style.display = "none";


var blobs = document.getElementById("animation");
blobs.style.display = "none";

}
hideAnimation();

function showAnimation(){
var bg = document.getElementById("animation-bg");
bg.style.display = "block";



var blobs = document.getElementById("animation");
blobs.style.display = "block";


}

</script>
@endsection
