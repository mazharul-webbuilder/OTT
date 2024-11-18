<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FFMpeg Encrypted HLS | Protone Media</title>

        {{-- <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet"> --}}
      
        <link href="https://vjs.zencdn.net/7.15.4/video-js.css" rel="stylesheet">
        <script src="https://vjs.zencdn.net/7.15.4/video.js"></script>

        <script src="{{ asset('js/app.js') }}" defer></script>
        
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
           

            <div class="max-w-6xl w-full mx-auto sm:px-6 lg:px-8">
                {{-- <video-js id="my_video_1" class="vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
                    <source src="/storage/videos/aman.m3u8" type="application/x-mpegURL">
                </video-js> --}}

                {{-- <script src="https://unpkg.com/video.js/dist/video.js"></script> --}}
                {{-- <script src="https://unpkg.com/@videojs/http-streaming/dist/videojs-http-streaming.js"></script> --}}

                {{-- <script>
                    var player = videojs('my_video_1');
                </script> --}}

                {{-- <video id="my-video" class="video-js" controls preload="auto" width="640" height="264">
                    <source src="/storage/videos/redfield.m3u8" type="application/x-mpegURL">
                    <p class="vjs-no-js">Your browser does not support the video tag.</p>
                  </video>
                  
                  <script>
                    // Create a Video.js player instance
                    const player = videojs('my-video');
                  </script> --}}

                  <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-1">
                        <div class="p-6">
                            <div class="flex items-center">
                                <input type="hidden" name="file" id="file" />
                                <x-input.uppy />
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </body>
</html>