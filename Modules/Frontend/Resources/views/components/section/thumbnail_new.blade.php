<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Enhanced HLS Player</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        :root {
            --bg: #000;
            --control-bg: rgba(10, 10, 10, 0.9);
            --accent: #ff2a86;
            /* pink scrubber */
            --muted: #bdbdbd;
            --height-controls: 56px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            background: var(--bg);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
        }

        .player-wrap {
            max-width: 1280px;
            margin: 16px auto;
            position: relative;
            background: #000;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
        }

        .video-el {
            width: 100%;
            height: calc(100vw * 9/16);
            max-height: 720px;
            background: #000;
            display: block;
            object-fit: cover;
        }

        /* center play big button */
        .big-play {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 66px;
            height: 66px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            cursor: pointer;
            border: 2px solid rgba(255, 255, 255, 0.06);
            transition: transform 0.2s, opacity 0.2s;
            z-index: 10;
        }

        .big-play:hover {
            transform: translate(-50%, -50%) scale(1.1);
            background: rgba(0, 0, 0, 0.7);
        }

        .big-play svg {
            width: 28px;
            height: 28px;
            fill: #fff;
        }

        /* controls area */
        .controls {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            /* height: var(--height-controls); */

            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0.35) 30%, var(--control-bg) 100%);
            /* padding: 8px 12px; */

            box-sizing: border-box;
            transition: opacity 0.3s;
            z-index: 20;
        }

        /* progress / scrub */
        .progress-wrap {
            height: 12px;
            display: flex;
            align-items: center;
            width: 100%;
            margin-bottom: 8px;
            position: relative;
            cursor: pointer;
        }

        .progress-bar {
            height: 4px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 3px;
            width: 100%;
            position: relative;
        }

        .progress-buffer {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 3px;
            transition: width 0.2s;
        }

        .progress-played {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--accent), #ff66aa);
            border-radius: 3px;
            transition: width 0.2s;
        }

        .progress-hover {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            display: none;
        }

        .scrubber {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: 0 0 0 6px rgba(0, 0, 0, 0.4);
            left: 0;
            transition: left .08s linear;
            opacity: 0;
        }

        .progress-wrap:hover .scrubber {
            opacity: 1;
        }

        .controls-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            color: #fff;
            font-size: 13px;
        }

        .left-controls,
        .right-controls {
            display: flex;
            align-items: center;
            gap: 10px
        }

        /* small icons and buttons */
        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s;
        }

        .icon-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            pointer-events: none
        }

        /* Skip buttons with labels */
        .skip-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            min-width: 42px;
            padding: 4px 2px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .skip-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* .skip-btn svg {
            width: 20px;
            height: 20px;
            margin-bottom: 2px;
        } */

        .skip-btn span {
            font-size: 9px;
            font-weight: 600;
            opacity: 0.8;
        }

        /* time and volume */
        .time {
            color: var(--muted);
            font-weight: 600;
            min-width: 100px;
            text-align: center;
        }

        .volume {
            display: flex;
            align-items: center;
            gap: 6px
        }

        .vol-slider {
            width: 90px;
            height: 4px;
            border-radius: 2px;
            background: rgba(255, 255, 255, 0.2);
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .vol-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            cursor: pointer;
        }

        .vol-slider::-moz-range-thumb {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--accent);
            cursor: pointer;
            border: none;
        }

        /* settings badge like 'FHD' */
        .quality-badge {
            background: #ff0a6a;
            color: #fff;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
            margin-left: 6px;
        }

        /* buffering spinner */
        .spinner {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 15;
        }

        .spinner .ring {
            box-sizing: border-box;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.08);
            border-top-color: var(--accent);
            animation: spin 1s linear infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        /* Settings menu */
        .settings-menu {
            position: absolute;
            bottom: 60px;
            right: 10px;
            background: var(--control-bg);
            border-radius: 6px;
            padding: 8px 0;
            min-width: 150px;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            z-index: 30;
        }

        .settings-menu.show {
            display: block;
        }

        .menu-item {
            padding: 8px 16px;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .menu-item.active {
            color: var(--accent);
        }

        /* Keyboard shortcut helper */
        .keyboard-help {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            display: none;
            z-index: 25;
        }

        .keyboard-help.show {
            display: block;
        }

        .key-shortcut {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 6px;
            border-radius: 4px;
            margin: 0 4px;
        }

        /* ===================== Responsive CSS: Single line controls ===================== */

        /* Small devices: up to 480px (mobile) */
        @media (max-width: 480px) {
            .player-wrap {
                margin: 8px;
                border-radius: 4px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            }

            .video-el {
                height: calc(100vw * 9 / 16);
            }

            .controls-row {
                flex-direction: row !important;
                /* keep everything in one line */
                justify-content: space-between;
                gap: 4px;
                /* smaller gap */
                font-size: 11px;
                overflow-x: auto;
                /* allow horizontal scroll if needed */
                padding: 0 4px;
            }

            .left-controls,
            .right-controls {
                flex-wrap: nowrap !important;
                gap: 4px;
                align-items: center;
            }

            .icon-btn,
            .btn {
                width: 28px;
                height: 28px;
                padding: 0;
            }

            .volume {
                gap: 4px;
            }

            .vol-slider {
                width: 60px;
            }

            .time {
                min-width: 60px;
                font-size: 10px;
                text-align: center;
            }

            .scrubber {
                width: 10px;
                height: 10px;
            }

            .big-play {
                width: 50px;
                height: 50px;
            }

            .big-play svg {
                width: 24px;
                height: 24px;
            }

            .settings-menu {
                min-width: 120px;
                bottom: 50px;
                right: 5px;
            }

            .quality-badge {
                font-size: 10px;
                padding: 2px 4px;
            }
        }

        /* Medium devices: 481px - 768px (tablet) */
        @media (min-width: 481px) and (max-width: 768px) {
            .controls-row {
                flex-direction: row !important;
                justify-content: space-between;
                gap: 6px;
                font-size: 12px;
                overflow-x: auto;
                padding: 0 6px;
            }

            .left-controls,
            .right-controls {
                flex-wrap: nowrap !important;
                gap: 6px;
                align-items: center;
            }

            .icon-btn,
            .btn {
                width: 32px;
                height: 32px;
            }

            .vol-slider {
                width: 80px;
            }

            .time {
                min-width: 70px;
                font-size: 11px;
            }

            .scrubber {
                width: 10px;
                height: 10px;
            }

            .big-play {
                width: 60px;
                height: 60px;
            }

            .big-play svg {
                width: 26px;
                height: 26px;
            }

            .settings-menu {
                min-width: 130px;
            }

            .quality-badge {
                font-size: 11px;
                padding: 3px 5px;
            }
        }

        /* Large devices: 769px+ (desktop) */
        @media (min-width: 769px) {
            .controls-row {
                flex-direction: row;
                gap: 8px;
            }

            .left-controls,
            .right-controls {
                gap: 10px;
            }

            .vol-slider {
                width: 90px;
            }

            .time {
                min-width: 100px;
                font-size: 13px;
            }

            .big-play {
                width: 66px;
                height: 66px;
            }

            .big-play svg {
                width: 28px;
                height: 28px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>

<body>

    <div class="player-wrap" id="playerWrap">
        <!-- Video element -->
        <video id="video" class="video-el" src="{{ $data }}" playsinline crossorigin="anonymous" poster="{{ $thumbnail_image }}"></video>


        <!-- Big centered play -->
        <div id="bigPlay" class="big-play" aria-label="Play">
            <svg viewBox="0 0 24 24">
                <path d="M8 5v14l11-7z" />
            </svg>
        </div>

        <!-- Buffer spinner -->
        <div id="spinner" class="spinner" aria-hidden="true">
            <div class="ring"></div>
        </div>

        <!-- Keyboard help -->
        <div id="keyboardHelp" class="keyboard-help">
            <div>Space: Play/Pause</div>
            <div>←/→: Seek 5s</div>
            <div>↑/↓: Volume</div>
            <div>F: Fullscreen</div>
            <div>M: Mute</div>
        </div>

        <!-- Settings menu -->
        <div id="settingsMenu" class="settings-menu">
            <div class="menu-header"
                style="padding: 8px 16px; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.1);">Quality
            </div>
            <div class="menu-item" data-quality="auto">Auto <span class="checkmark">✓</span></div>
            <div class="menu-item" data-quality="1080">1080p (FHD)</div>
            <div class="menu-item" data-quality="720">720p (HD)</div>
            <div class="menu-item" data-quality="480">480p (SD)</div>
            <div class="menu-item" data-quality="360">360p (LD)</div>
        </div>

        <!-- Controls -->
        <div class="controls" id="controls">
            <div class="progress-wrap" id="progressWrap" role="slider" aria-label="Seek" tabindex="0">
                <div class="progress-bar" id="progressBar">
                    <div class="progress-buffer" id="progressBuffer"></div>
                    <div class="progress-played" id="progressPlayed"></div>
                    <div class="progress-hover" id="progressHover"></div>
                    <div class="scrubber" id="scrubber" aria-hidden="true"></div>
                </div>
            </div>

            <div class="controls-row d-flex justify-content-between align-items-center bg-dark">
                <!-- Left controls -->
                <div class="left-controls d-flex align-items-center gap-2">
                    <!-- Rewind button (10s) -->
                    <button class="btn btn-dark d-flex align-items-center justify-content-center gap-1" id="btnRewind" title="Back 10s" aria-label="Back 10 seconds">
                        <i class="fa-solid fa-rotate-left fa-lg text-white"></i>


                    </button>

                    <!-- Play/Pause button -->
                    <button class="btn btn-dark d-flex align-items-center justify-content-center" id="btnPlay" title="Play/Pause" aria-label="Play or pause">
                        <i class="fa-solid fa-play fa-lg text-white" id="playIcon"></i>
                    </button>

                    <script>
                        const btnPlay = document.getElementById('btnPlay');
                        const playIcon = document.getElementById('playIcon');
                        let isPlaying = false; // Player initially paused

                        btnPlay.addEventListener('click', () => {
                            if (isPlaying) {
                                // Pause state
                                playIcon.classList.remove('fa-pause');
                                playIcon.classList.add('fa-play');
                                isPlaying = false;


                                // এখানে তুমি video.pause() ব্যবহার করতে পারো যদি video tag থাকে
                                // document.getElementById('videoPlayer').pause();
                            } else {
                                // Play state
                                playIcon.classList.remove('fa-play');
                                playIcon.classList.add('fa-pause');
                                isPlaying = true;
                                // document.getElementById('videoPlayer').play();
                            }
                        });
                    </script>


                    <script>
                        document.getElementById('bigPlay').addEventListener('click', function() {
                            console.log('Big play button clicked');
                            playIcon.classList.remove('fa-play');
                            playIcon.classList.add('fa-pause');
                            isPlaying = true;
                        });
                    </script>

                    <!-- Forward button (10s) -->
                    <button class="btn btn-dark d-flex align-items-center justify-content-center gap-1" id="btnForward" title="Forward 10s" aria-label="Forward 10 seconds">
                        <i class="fa-solid fa-rotate-right fa-lg text-white"></i>

                    </button>

                    <!-- Volume control -->
                    <div class="volume d-flex align-items-center gap-1">
                        <button class="btn btn-dark d-flex align-items-center justify-content-center" id="btnMute" title="Mute" aria-label="Mute or unmute">
                            <i class="fa-solid fa-volume-high fa-lg text-white" id="muteIcon"></i>
                        </button>
                        <input id="vol" class="form-range" type="range" min="0" max="1" step="0.05" value="1" style="width:100px;" aria-label="Volume">
                    </div>

                    <!-- Time display -->
                    <div class="time text-white small" id="timeText">0:00 / 0:00</div>
                </div>

                <!-- Right controls -->
                <div class="right-controls d-flex align-items-center gap-2">
                    <!-- Subtitles/CC -->
                    <button class="btn btn-dark d-none align-items-center justify-content-center" id="btnCaptions" title="Subtitles" aria-label="Toggle captions">
                        <i class="fa-solid fa-closed-captioning fa-lg text-white"></i>
                    </button>

                    <!-- Quality badge and settings -->
                    <div id="qualityWrap" class="d-none align-items-center gap-1">
                        <div class="quality-badge bg-secondary text-white px-2 py-1 rounded" id="qualityBadge" style="font-size:0.75rem;">AUTO</div>
                        <button class="btn btn-dark d-flex align-items-center justify-content-center" id="btnSettings" title="Settings" aria-label="Settings">
                            <i class="fa-solid fa-gear fa-lg text-white"></i>
                        </button>
                    </div>

                    <!-- Fullscreen -->
                    <button class="btn btn-dark d-flex align-items-center justify-content-center" id="btnFullscreen" title="Fullscreen" aria-label="Fullscreen">
                        <i class="fa-solid fa-expand fa-lg text-white" id="fullscreenIcon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.0/dist/hls.min.js"></script>
    <script>
        (function() {
            const HLS_URL = '{{ $data }}'; // replace with your .m3u8
            const video = document.getElementById('video');
            const bigPlay = document.getElementById('bigPlay');
            const spinner = document.getElementById('spinner');
            const playBtn = document.getElementById('btnPlay');
            const playIcon = document.getElementById('playIcon');
            const btnRewind = document.getElementById('btnRewind');
            const btnForward = document.getElementById('btnForward');
            const scrubber = document.getElementById('scrubber');
            const progressBar = document.getElementById('progressBar');
            const progressPlayed = document.getElementById('progressPlayed');
            const progressBuffer = document.getElementById('progressBuffer');
            const progressHover = document.getElementById('progressHover');
            const timeText = document.getElementById('timeText');
            const vol = document.getElementById('vol');
            const btnMute = document.getElementById('btnMute');
            const muteIcon = document.getElementById('muteIcon');
            const btnFullscreen = document.getElementById('btnFullscreen');
            const fullscreenIcon = document.getElementById('fullscreenIcon');
            const btnCaptions = document.getElementById('btnCaptions');
            const qualityBadge = document.getElementById('qualityBadge');
            const btnSettings = document.getElementById('btnSettings');
            const settingsMenu = document.getElementById('settingsMenu');
            const keyboardHelp = document.getElementById('keyboardHelp');

            let hls; // HLS instance
            let isSettingsOpen = false;
            let isHelpVisible = false;

            // Setup HLS
            function attachHls(url) {
                if (video.canPlayType('application/vnd.apple.mpegurl')) {
                    // Native HLS (iOS Safari, some browsers)
                    video.src = url;
                } else if (Hls.isSupported()) {
                    hls = new Hls({
                        capLevelToPlayerSize: true,
                        maxBufferLength: 30,
                        maxMaxBufferLength: 60,
                        xhrSetup: function(xhr, url) {
                            // Add referer header if needed: xhr.setRequestHeader('Referer', 'http://player.test');
                        }
                    });
                    hls.loadSource(url);
                    hls.attachMedia(video);
                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                        // Setup quality menu items
                        setupQualityMenu();
                        updateQualityBadge();
                    });

                    // update quality badge on level switch
                    hls.on(Hls.Events.LEVEL_SWITCHED, function(evt, data) {
                        updateQualityBadge();
                    });

                    // when BUFFERING/STALL happens, hls.js will trigger events
                    hls.on(Hls.Events.BUFFER_STALLED, () => showSpinner(true));
                } else {
                    console.error('HLS not supported in this browser');
                }
            }

            function setupQualityMenu() {
                const menuItems = settingsMenu.querySelectorAll('.menu-item');
                menuItems.forEach(item => {
                    item.classList.remove('active');
                    if (item.dataset.quality === 'auto') {
                        item.classList.add('active');
                    }

                    item.addEventListener('click', () => {
                        const quality = item.dataset.quality;
                        setQuality(quality);
                        menuItems.forEach(i => i.classList.remove('active'));
                        item.classList.add('active');
                        toggleSettingsMenu();
                    });
                });
            }

            function setQuality(quality) {
                if (!hls) return;

                if (quality === 'auto') {
                    hls.currentLevel = -1; // auto
                } else {
                    const level = parseInt(quality);
                    // Find the level that matches the requested quality
                    const levels = hls.levels;
                    for (let i = 0; i < levels.length; i++) {
                        if (levels[i].height === level) {
                            hls.currentLevel = i;
                            break;
                        }
                    }
                }
                updateQualityBadge();
            }

            function updateQualityBadge() {
                try {
                    if (!hls) {
                        qualityBadge.textContent = 'AUTO';
                        return;
                    }

                    const level = hls.levels && hls.levels[hls.currentLevel];
                    if (level && level.height) {
                        qualityBadge.textContent = level.height >= 1080 ? 'FHD' : (level.height >= 720 ? 'HD' : (level.height >= 480 ? 'SD' : 'LD'));
                    } else {
                        qualityBadge.textContent = 'AUTO';
                    }
                } catch (e) {
                    qualityBadge.textContent = 'AUTO';
                }
            }

            attachHls(HLS_URL);

            // play/pause
            function setPlayingUI(playing) {
                if (playing) {
                    playIcon.innerHTML = '<path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>'; // pause icon
                    bigPlay.style.display = 'none';
                } else {
                    playIcon.innerHTML = '<path d="M8 5v14l11-7z"/>'; // play icon
                    bigPlay.style.display = 'flex';
                }
            }

            function togglePlay() {
                if (video.paused || video.ended) {
                    video.play().catch(() => {
                        /* autoplay blocked, wait for user gesture */ });
                } else {
                    video.pause();
                }
            }

            bigPlay.addEventListener('click', togglePlay);
            playBtn.addEventListener('click', togglePlay);
            video.addEventListener('play', () => setPlayingUI(true));
            video.addEventListener('pause', () => setPlayingUI(false));
            video.addEventListener('ended', () => setPlayingUI(false));

            // rewind / forward
            btnRewind.addEventListener('click', () => {
                video.currentTime = Math.max(0, video.currentTime - 10);
            });
            btnForward.addEventListener('click', () => {
                video.currentTime = Math.min(video.duration || 0, video.currentTime + 10);
            });

            // volume controls
            vol.addEventListener('input', (e) => {
                video.volume = e.target.value;
                video.muted = video.volume === 0;
                updateMuteIcon();
            });

            function updateMuteIcon() {
                if (video.muted || video.volume === 0) {
                    muteIcon.innerHTML = '<path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>';
                } else {
                    muteIcon.innerHTML = '<path d="M5 9v6h4l5 5V4L9 9H5z"/><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>';
                }
            }

            btnMute.addEventListener('click', () => {
                video.muted = !video.muted;
                vol.value = video.muted ? 0 : video.volume || 1;
                updateMuteIcon();
            });

            // fullscreen
            function updateFullscreenIcon() {
                if (document.fullscreenElement) {
                    fullscreenIcon.innerHTML = '<path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>';
                } else {
                    fullscreenIcon.innerHTML = '<path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>';
                }
            }

            btnFullscreen.addEventListener('click', () => {
                const wrap = document.getElementById('playerWrap');
                if (!document.fullscreenElement) {
                    wrap.requestFullscreen().catch(() => {});
                } else {
                    document.exitFullscreen().catch(() => {});
                }
            });

            document.addEventListener('fullscreenchange', updateFullscreenIcon);

            // settings menu
            function toggleSettingsMenu() {
                isSettingsOpen = !isSettingsOpen;
                if (isSettingsOpen) {
                    settingsMenu.classList.add('show');
                } else {
                    settingsMenu.classList.remove('show');
                }
            }

            btnSettings.addEventListener('click', toggleSettingsMenu);

            // Close settings when clicking outside
            document.addEventListener('click', (e) => {
                if (isSettingsOpen && !btnSettings.contains(e.target) && !settingsMenu.contains(e.target)) {
                    toggleSettingsMenu();
                }
            });

            // captions toggle (placeholder — requires WebVTT or tracks)
            let captionsOn = false;
            btnCaptions.addEventListener('click', () => {
                captionsOn = !captionsOn;
                // If you have tracks: toggle track.mode = captionsOn ? 'showing' : 'hidden'
                btnCaptions.style.opacity = captionsOn ? '1' : '0.6';
            });

            // progress + scrubbing
            let isScrubbing = false;

            function formatTime(s) {
                if (!isFinite(s)) return '0:00';
                s = Math.floor(s);
                const m = Math.floor(s / 60),
                    sec = s % 60;
                return `${m}:${sec.toString().padStart(2, '0')}`;
            }

            function updateTimeUI() {
                const cur = video.currentTime || 0;
                const dur = video.duration || 0;
                timeText.textContent = `${formatTime(cur)} / ${formatTime(dur)}`;
                // played %
                const pct = dur ? (cur / dur) * 100 : 0;
                progressPlayed.style.width = pct + '%';
                scrubber.style.left = pct + '%';
                // buffer bar: find the last buffered range that contains currentTime
                try {
                    const buffered = video.buffered;
                    if (buffered.length) {
                        // show buffered extent as percent up to last range end
                        const bufEnd = buffered.end(buffered.length - 1);
                        const bufPct = dur ? (bufEnd / dur) * 100 : 0;
                        progressBuffer.style.width = Math.min(100, bufPct) + '%';
                    } else {
                        progressBuffer.style.width = '0%';
                    }
                } catch (e) {
                    progressBuffer.style.width = '0%';
                }
            }

            // Hover time preview
            progressBar.addEventListener('mousemove', (e) => {
                const rect = progressBar.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const pct = Math.max(0, Math.min(1, x / rect.width));
                progressHover.style.width = (pct * 100) + '%';
                progressHover.style.display = 'block';
            });

            progressBar.addEventListener('mouseleave', () => {
                progressHover.style.display = 'none';
            });

            // update animation frame
            let rafId;

            function rafLoop() {
                updateTimeUI();
                rafId = requestAnimationFrame(rafLoop);
            }
            video.addEventListener('playing', () => {
                showSpinner(false);
                rafLoop();
            });
            video.addEventListener('pause', () => {
                cancelAnimationFrame(rafId);
                updateTimeUI();
            });
            video.addEventListener('timeupdate', updateTimeUI);
            video.addEventListener('progress', updateTimeUI);

            // waiting / buffering events
            function showSpinner(show) {
                spinner.style.display = show ? 'flex' : 'none';
            }
            video.addEventListener('waiting', () => {
                showSpinner(true);
            });
            video.addEventListener('stalled', () => {
                showSpinner(true);
            });
            video.addEventListener('canplay', () => {
                showSpinner(false);
            });
            video.addEventListener('canplaythrough', () => {
                showSpinner(false);
            });

            // click on progress to seek
            function seekFromEvent(e) {
                const rect = progressBar.getBoundingClientRect();
                const x = (e.clientX ?? (e.touches && e.touches[0].clientX)) - rect.left;
                const pct = Math.max(0, Math.min(1, x / rect.width));
                const dur = video.duration || 0;
                video.currentTime = pct * dur;
                updateTimeUI();
            }

            progressBar.addEventListener('click', (e) => seekFromEvent(e));
            progressBar.addEventListener('touchstart', (e) => seekFromEvent(e));

            // keyboard accessibility: left/right arrows seek, space toggle
            document.addEventListener('keydown', (e) => {
                if (document.activeElement && (document.activeElement.tagName === 'INPUT' || document.activeElement.isContentEditable)) return;

                if (e.code === 'Space') {
                    e.preventDefault();
                    togglePlay();
                }
                if (e.key === 'ArrowLeft') {
                    video.currentTime = Math.max(0, video.currentTime - 5);
                }
                if (e.key === 'ArrowRight') {
                    video.currentTime = Math.min(video.duration || 0, video.currentTime + 5);
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    video.volume = Math.min(1, video.volume + 0.05);
                    vol.value = video.volume;
                    updateMuteIcon();
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    video.volume = Math.max(0, video.volume - 0.05);
                    vol.value = video.volume;
                    updateMuteIcon();
                }
                if (e.key === 'f' || e.key === 'F') {
                    e.preventDefault();
                    btnFullscreen.click();
                }
                if (e.key === 'm' || e.key === 'M') {
                    e.preventDefault();
                    btnMute.click();
                }
                if (e.key === '?') {
                    e.preventDefault();
                    toggleKeyboardHelp();
                }
            });

            function toggleKeyboardHelp() {
                isHelpVisible = !isHelpVisible;
                if (isHelpVisible) {
                    keyboardHelp.classList.add('show');
                    setTimeout(() => {
                        keyboardHelp.classList.remove('show');
                        isHelpVisible = false;
                    }, 3000);
                } else {
                    keyboardHelp.classList.remove('show');
                }
            }

            // prevent context menu or accidental dragging
            document.getElementById('playerWrap').addEventListener('contextmenu', (e) => e.preventDefault());

            // initial UI states
            setPlayingUI(false);
            vol.value = 1;
            updateMuteIcon();

            // auto-hide controls (optional)
            let controlsTimeout;
            const controlsEl = document.getElementById('controls');

            function showControls() {
                controlsEl.style.opacity = 1;
                controlsEl.style.pointerEvents = '';
                clearTimeout(controlsTimeout);
                controlsTimeout = setTimeout(() => {
                    if (!video.paused) {
                        controlsEl.style.opacity = 0;
                        controlsEl.style.pointerEvents = 'none';
                        bigPlay.style.opacity = 0;
                    }
                }, 3500);
            }

            function resetControlsTimeout() {
                showControls();
                if (video.paused) {
                    bigPlay.style.opacity = 1;
                }
            }

            // show controls initially
            controlsEl.style.transition = 'opacity .25s';
            bigPlay.style.transition = 'opacity .25s';
            resetControlsTimeout();
            document.getElementById('playerWrap').addEventListener('mousemove', resetControlsTimeout);
            document.getElementById('playerWrap').addEventListener('touchstart', resetControlsTimeout);

            // defensive: handle unreachable stream
            video.addEventListener('error', (e) => {
                console.error('Video error', e);
                showSpinner(false);
            });

        })();
    </script>
</body>

</html>