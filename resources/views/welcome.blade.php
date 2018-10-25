<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Voice Transcription
                </div>

                <div>
                    <form method="post" action="/upload" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label for="upload">Upload Mp3 Audio</label>
                            <input type="file" class="form-control" id="upload" aria-describedby="emailHelp" name="upload">
                            <small id="emailHelp" class="form-text text-muted">Kindly upload an Mp3 audio file here</small>
                        </div>
                       
                        <button type="submit" id="transcript" class="btn btn-primary">Transcribe</button>
                    </form>
                </div>

                <div id="mymessage" style="text-align:center; color:red; font-weight:bold;">
                </div>

                <div style="padding:20px; text-align:justify;font-weight:bold; font-size:16px; padding-top:60px; color:blue;">
                @isset($data)
                    @foreach($data as $speech)
                        <p>{{ $speech }}</p>
                    @endforeach
                @endif
                 </div>
                
            </div>
        </div>
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function(){
                $("#transcript").click(function(){
                    document.getElementById("mymessage").innerHTML="Transcribing please wait ...";
                });
            })
        </script>
    </body>
</html>
