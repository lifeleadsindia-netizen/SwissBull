/**
 * Video Player Extension
 * Provides enhanced video playback functionality with controls, tracking, and features
 */

class VideoPlayerExtension {
    constructor(config = {}) {
        this.config = {
            containerId: config.containerId || 'video-player-container',
            videoElement: config.videoElement || 'main-video',
            autoplay: config.autoplay !== undefined ? config.autoplay : false,
            controls: config.controls !== undefined ? config.controls : true,
            fullscreenEnabled: config.fullscreenEnabled !== undefined ? config.fullscreenEnabled : true,
            qualityOptions: config.qualityOptions || ['auto', '720p', '480p', '360p'],
            enableProgress: config.enableProgress !== undefined ? config.enableProgress : true,
            enableSubtitles: config.enableSubtitles !== undefined ? config.enableSubtitles : false,
            ...config
        };

        this.videoElement = document.getElementById(this.config.videoElement);
        this.container = document.getElementById(this.config.containerId);
        this.isPlaying = false;
        this.currentQuality = 'auto';
        this.progressData = {};
        this.bookmarks = [];

        this.init();
    }

    /**
     * Initialize the video player
     */
    init() {
        if (!this.videoElement || !this.container) {
            console.error('VideoPlayerExtension: Required elements not found');
            return;
        }

        this.setupEventListeners();
        this.createCustomControls();
        this.loadProgress();
    }

    /**
     * Setup event listeners for video element
     */
    setupEventListeners() {
        this.videoElement.addEventListener('play', () => this.onPlay());
        this.videoElement.addEventListener('pause', () => this.onPause());
        this.videoElement.addEventListener('timeupdate', () => this.onTimeUpdate());
        this.videoElement.addEventListener('ended', () => this.onVideoEnd());
        this.videoElement.addEventListener('loadedmetadata', () => this.onMetadataLoaded());
        this.videoElement.addEventListener('error', () => this.onVideoError());
        this.videoElement.addEventListener('fullscreenchange', () => this.onFullscreenChange());
    }

    /**
     * Create custom playback controls
     */
    createCustomControls() {
        if (!this.config.controls) return;

        const controlsHTML = `
            <div class="video-controls">
                <div class="progress-bar-container">
                    <progress id="video-progress" class="progress-bar" min="0" max="100" value="0"></progress>
                    <div class="progress-tooltip"></div>
                </div>
                
                <div class="controls-bottom">
                    <div class="left-controls">
                        <button class="control-btn play-btn" title="Play/Pause">
                            <i class="icon-play"></i>
                        </button>
                        <button class="control-btn volume-btn" title="Volume">
                            <i class="icon-volume"></i>
                        </button>
                        <input type="range" class="volume-slider" min="0" max="100" value="100">
                        <span class="time-display">0:00 / 0:00</span>
                    </div>
                    
                    <div class="right-controls">
                        <button class="control-btn bookmark-btn" title="Add Bookmark">
                            <i class="icon-bookmark"></i>
                        </button>
                        <button class="control-btn quality-btn" title="Quality">
                            <span>Auto</span>
                            <div class="quality-menu" style="display:none;">
                                ${this.config.qualityOptions.map(q => 
                                    `<div class="quality-option" data-quality="${q}">${q}</div>`
                                ).join('')}
                            </div>
                        </button>
                        <button class="control-btn settings-btn" title="Settings">
                            <i class="icon-settings"></i>
                        </button>
                        <button class="control-btn fullscreen-btn" title="Fullscreen">
                            <i class="icon-fullscreen"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        const controlsContainer = document.createElement('div');
        controlsContainer.className = 'video-player-controls';
        controlsContainer.innerHTML = controlsHTML;
        this.container.appendChild(controlsContainer);

        this.attachControlListeners();
    }

    /**
     * Attach event listeners to control buttons
     */
    attachControlListeners() {
        const playBtn = this.container.querySelector('.play-btn');
        const volumeBtn = this.container.querySelector('.volume-btn');
        const volumeSlider = this.container.querySelector('.volume-slider');
        const qualityBtn = this.container.querySelector('.quality-btn');
        const bookmarkBtn = this.container.querySelector('.bookmark-btn');
        const fullscreenBtn = this.container.querySelector('.fullscreen-btn');
        const progressBar = this.container.querySelector('.progress-bar');
        const qualityMenu = this.container.querySelector('.quality-menu');
        const qualityOptions = this.container.querySelectorAll('.quality-option');

        // Play/Pause
        if (playBtn) {
            playBtn.addEventListener('click', () => this.togglePlayPause());
        }

        // Volume control
        if (volumeSlider) {
            volumeSlider.addEventListener('input', (e) => {
                this.setVolume(e.target.value);
            });
        }

        if (volumeBtn) {
            volumeBtn.addEventListener('click', () => this.toggleMute());
        }

        // Quality selection
        if (qualityBtn) {
            qualityBtn.addEventListener('click', () => {
                qualityMenu.style.display = qualityMenu.style.display === 'none' ? 'block' : 'none';
            });
        }

        qualityOptions.forEach(option => {
            option.addEventListener('click', (e) => {
                this.setQuality(e.target.dataset.quality);
                qualityMenu.style.display = 'none';
                qualityBtn.querySelector('span').textContent = e.target.dataset.quality;
            });
        });

        // Bookmark
        if (bookmarkBtn) {
            bookmarkBtn.addEventListener('click', () => this.addBookmark());
        }

        // Fullscreen
        if (fullscreenBtn && this.config.fullscreenEnabled) {
            fullscreenBtn.addEventListener('click', () => this.toggleFullscreen());
        }

        // Progress bar scrubbing
        if (progressBar) {
            progressBar.addEventListener('click', (e) => this.seek(e));
            progressBar.addEventListener('mousemove', (e) => this.updateProgressTooltip(e));
        }

        // Close quality menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.container.querySelector('.quality-btn').contains(e.target) && 
                !qualityMenu.contains(e.target)) {
                qualityMenu.style.display = 'none';
            }
        });
    }

    /**
     * Toggle play/pause
     */
    togglePlayPause() {
        if (this.videoElement.paused) {
            this.videoElement.play();
        } else {
            this.videoElement.pause();
        }
    }

    /**
     * Play video
     */
    play() {
        this.videoElement.play();
    }

    /**
     * Pause video
     */
    pause() {
        this.videoElement.pause();
    }

    /**
     * Set volume (0-100)
     */
    setVolume(value) {
        const volume = Math.max(0, Math.min(100, value)) / 100;
        this.videoElement.volume = volume;
        this.updateVolumeIcon(volume);
    }

    /**
     * Toggle mute
     */
    toggleMute() {
        if (this.videoElement.volume > 0) {
            this.previousVolume = this.videoElement.volume;
            this.videoElement.volume = 0;
        } else {
            this.videoElement.volume = this.previousVolume || 0.5;
        }
        this.updateVolumeIcon(this.videoElement.volume);
    }

    /**
     * Update volume icon based on current volume
     */
    updateVolumeIcon(volume) {
        const volumeBtn = this.container.querySelector('.volume-btn i');
        const volumeSlider = this.container.querySelector('.volume-slider');
        
        if (volumeSlider) {
            volumeSlider.value = volume * 100;
        }

        if (volumeBtn) {
            if (volume === 0) {
                volumeBtn.className = 'icon-volume-muted';
            } else if (volume < 0.5) {
                volumeBtn.className = 'icon-volume-low';
            } else {
                volumeBtn.className = 'icon-volume-high';
            }
        }
    }

    /**
     * Seek to specific time
     */
    seek(event) {
        const rect = event.target.getBoundingClientRect();
        const percentage = (event.clientX - rect.left) / rect.width;
        this.videoElement.currentTime = percentage * this.videoElement.duration;
    }

    /**
     * Set video quality
     */
    setQuality(quality) {
        this.currentQuality = quality;
        const currentTime = this.videoElement.currentTime;
        const wasPlaying = !this.videoElement.paused;

        // Emit quality change event for backend to handle quality switching
        this.emit('quality-changed', { quality, currentTime });

        if (wasPlaying) {
            this.videoElement.play();
        }
    }

    /**
     * Add bookmark at current timestamp
     */
    addBookmark() {
        const timestamp = this.videoElement.currentTime;
        const formattedTime = this.formatTime(timestamp);
        
        const bookmark = {
            time: timestamp,
            displayTime: formattedTime,
            createdAt: new Date().toISOString()
        };

        this.bookmarks.push(bookmark);
        this.saveBookmarks();
        this.emit('bookmark-added', bookmark);
    }

    /**
     * Remove bookmark
     */
    removeBookmark(time) {
        this.bookmarks = this.bookmarks.filter(b => b.time !== time);
        this.saveBookmarks();
        this.emit('bookmark-removed', { time });
    }

    /**
     * Get all bookmarks
     */
    getBookmarks() {
        return this.bookmarks;
    }

    /**
     * Save bookmarks to localStorage
     */
    saveBookmarks() {
        const videoId = this.videoElement.id;
        localStorage.setItem(`bookmarks_${videoId}`, JSON.stringify(this.bookmarks));
    }

    /**
     * Load bookmarks from localStorage
     */
    loadBookmarks() {
        const videoId = this.videoElement.id;
        const saved = localStorage.getItem(`bookmarks_${videoId}`);
        this.bookmarks = saved ? JSON.parse(saved) : [];
    }

    /**
     * Toggle fullscreen
     */
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            this.container.requestFullscreen().catch(err => {
                console.error('Fullscreen request failed:', err);
            });
        } else {
            document.exitFullscreen();
        }
    }

    /**
     * On play event
     */
    onPlay() {
        this.isPlaying = true;
        const playBtn = this.container.querySelector('.play-btn i');
        if (playBtn) playBtn.className = 'icon-pause';
        this.saveProgress();
    }

    /**
     * On pause event
     */
    onPause() {
        this.isPlaying = false;
        const playBtn = this.container.querySelector('.play-btn i');
        if (playBtn) playBtn.className = 'icon-play';
        this.saveProgress();
    }

    /**
     * On time update (progress)
     */
    onTimeUpdate() {
        this.updateProgressBar();
        this.updateTimeDisplay();
    }

    /**
     * On video end
     */
    onVideoEnd() {
        this.isPlaying = false;
        this.progressData.completed = true;
        this.saveProgress();
        this.emit('video-ended');
    }

    /**
     * On metadata loaded
     */
    onMetadataLoaded() {
        this.updateTimeDisplay();
    }

    /**
     * On video error
     */
    onVideoError() {
        console.error('Video playback error:', this.videoElement.error);
        this.emit('video-error', this.videoElement.error);
    }

    /**
     * On fullscreen change
     */
    onFullscreenChange() {
        this.emit('fullscreen-changed', { fullscreen: !!document.fullscreenElement });
    }

    /**
     * Update progress bar
     */
    updateProgressBar() {
        const progressBar = this.container.querySelector('.progress-bar');
        if (progressBar && this.videoElement.duration) {
            const percentage = (this.videoElement.currentTime / this.videoElement.duration) * 100;
            progressBar.value = percentage;
        }
    }

    /**
     * Update progress tooltip
     */
    updateProgressTooltip(event) {
        const progressBar = this.container.querySelector('.progress-bar');
        const tooltip = this.container.querySelector('.progress-tooltip');
        
        if (!progressBar || !tooltip) return;

        const rect = progressBar.getBoundingClientRect();
        const percentage = (event.clientX - rect.left) / rect.width;
        const time = percentage * this.videoElement.duration;

        tooltip.textContent = this.formatTime(time);
        tooltip.style.left = (percentage * 100) + '%';
    }

    /**
     * Update time display
     */
    updateTimeDisplay() {
        const timeDisplay = this.container.querySelector('.time-display');
        if (timeDisplay) {
            const current = this.formatTime(this.videoElement.currentTime);
            const duration = this.formatTime(this.videoElement.duration);
            timeDisplay.textContent = `${current} / ${duration}`;
        }
    }

    /**
     * Format time in MM:SS format
     */
    formatTime(seconds) {
        if (!seconds || isNaN(seconds)) return '0:00';
        
        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    }

    /**
     * Save progress to localStorage
     */
    saveProgress() {
        const videoId = this.videoElement.id || this.videoElement.src;
        this.progressData = {
            videoId,
            currentTime: this.videoElement.currentTime,
            duration: this.videoElement.duration,
            percentage: (this.videoElement.currentTime / this.videoElement.duration) * 100,
            lastUpdated: new Date().toISOString()
        };

        localStorage.setItem(`video_progress_${videoId}`, JSON.stringify(this.progressData));
        this.emit('progress-saved', this.progressData);
    }

    /**
     * Load progress from localStorage
     */
    loadProgress() {
        const videoId = this.videoElement.id || this.videoElement.src;
        const saved = localStorage.getItem(`video_progress_${videoId}`);
        
        if (saved) {
            this.progressData = JSON.parse(saved);
            // Don't auto-resume, let user decide
            this.emit('progress-loaded', this.progressData);
        }
    }

    /**
     * Resume from saved progress
     */
    resumeProgress() {
        if (this.progressData.currentTime) {
            this.videoElement.currentTime = this.progressData.currentTime;
            this.videoElement.play();
        }
    }

    /**
     * Get watch statistics
     */
    getStats() {
        return {
            watchedPercentage: this.progressData.percentage || 0,
            watchedTime: this.progressData.currentTime || 0,
            totalTime: this.videoElement.duration || 0,
            completed: this.progressData.completed || false
        };
    }

    /**
     * Clear progress
     */
    clearProgress() {
        const videoId = this.videoElement.id || this.videoElement.src;
        localStorage.removeItem(`video_progress_${videoId}`);
        this.progressData = {};
    }

    /**
     * Event emitter
     */
    emit(eventName, data = {}) {
        const event = new CustomEvent(`video-player:${eventName}`, { 
            detail: data 
        });
        this.container.dispatchEvent(event);
    }

    /**
     * Event listener
     */
    on(eventName, callback) {
        this.container.addEventListener(`video-player:${eventName}`, (e) => {
            callback(e.detail);
        });
    }

    /**
     * Destroy player
     */
    destroy() {
        this.videoElement.removeEventListener('play', () => this.onPlay());
        this.videoElement.removeEventListener('pause', () => this.onPause());
        this.videoElement.removeEventListener('timeupdate', () => this.onTimeUpdate());
        this.videoElement.removeEventListener('ended', () => this.onVideoEnd());
    }
}

// Export for use as module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VideoPlayerExtension;
}
