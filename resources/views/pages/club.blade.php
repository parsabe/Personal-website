<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parsa Besharat - Club</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

    <style>
        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #000;
        }
        canvas {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        .controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }
    </style>
</head>

<body>

    <canvas></canvas>
    
    <audio preload="none" id="audio" src="{{ asset('main.mp3') }}" loop></audio>

    <div class="controls">
        <button id="fr" onclick="p()" class="foo-button mdc-button mdc-button--raised">
            <span class="mdc-button__label">Play Music</span>
        </button>
        <button id="lo" onclick="p()" style="display:none;" class="foo-button mdc-button mdc-button--raised">
            <span class="mdc-button__label">Pause Music</span>
        </button>
    </div>

    <!-- External ESM Javascript Module -->
    <script type="module" src="{{ asset('js/club.js') }}"></script>
</body>
</html>