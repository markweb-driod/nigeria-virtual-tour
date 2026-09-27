/**
 * Virtual Tour Engine
 * Wraps Pannellum for the Nigerian Virtual Tour System
 */

class VirtualTourEngine {
    constructor(config) {
        this.config = config;
        this.viewer = null;
        this.currentSceneId = config.tour.defaultScene;
        this.sessionUuid = null;
        this.sessionId = null;
        this.attractionId = config.attractionId;
        this.isAudioPlaying = false;
        this.currentAudio = null;
        this.fpsReadings = [];
        this.lastFrameTime = null;
        this.fpsInterval = null;
        this.loadStartTime = null;
        this.scenesVisited = new Set();
        this.hotspotPanel = null;
        this.infoPanel = null;
    }

    /**
     * Initialize the Pannellum viewer
     */
    init() {
        this.loadStartTime = performance.now();
        this._startAnalyticsSession();

        const pannellumScenes = this._buildPannellumConfig();

        this.viewer = pannellum.viewer('panorama-container', {
            default: {
                firstScene: this.currentSceneId,
                sceneFadeDuration: 700,
                autoLoad: true,
                showControls: false,
                keyboardZoom: true,
                mouseZoom: true,
                draggable: true,
                disableKeyboardCtrl: false,
            },
            scenes: pannellumScenes,
        });

        this.viewer.on('load', () => this._onSceneLoad());
        this.viewer.on('scenechange', (sceneId) => this._onSceneChange(sceneId));
        this.viewer.on('error', (e) => console.error('Pannellum error:', e));

        this._startFpsMonitor();
        this._bindKeyboard();
        this._updateSceneUI(this.currentSceneId);

        window.addEventListener('beforeunload', () => this._endAnalyticsSession());
    }

    /**
     * Convert our tour config format to Pannellum's format
     */
    _buildPannellumConfig() {
        const scenes = {};

        Object.entries(this.config.scenes).forEach(([sceneId, scene]) => {
            const hotspots = scene.hotspots.map(hs => {
                const base = {
                    pitch: hs.pitch,
                    yaw: hs.yaw,
                    text: hs.text,
                    cssClass: `pnlm-hotspot-custom hs-${hs.type}`,
                };

                if (hs.type === 'scene') {
                    base.type = 'scene';
                    base.sceneId = hs.sceneId;
                } else {
                    base.type = 'info';
                    base.clickHandlerFunc = () => this._onHotspotClick(hs);
                }

                return base;
            });

            scenes[sceneId] = {
                title: scene.title,
                panorama: scene.panorama,
                yaw: scene.yaw || 0,
                pitch: scene.pitch || 0,
                hfov: scene.hfov || 100,
                hotSpots: hotspots,
            };
        });

        return scenes;
    }

    /**
     * Called when a scene finishes loading
     */
    _onSceneLoad() {
        const loadTime = performance.now() - this.loadStartTime;
        this._reportMetric('load_time_ms', loadTime);

        // Update scene info bar
        this._updateSceneUI(this.currentSceneId);

        // Preload adjacent scenes
        this._preloadAdjacentScenes(this.currentSceneId);

        // Track scene view
        if (this.sessionId) {
            const scene = this.config.scenes[this.currentSceneId];
            if (scene) {
                this.scenesVisited.add(this.currentSceneId);
                this._recordSceneView(scene.id);
            }
        }

        // Auto-play audio narration
        this._handleAudio(this.currentSceneId);
    }

    /**
     * Called when scene changes
     */
    _onSceneChange(sceneId) {
        const transitionStart = performance.now();
        this.currentSceneId = sceneId;
        this._updateSceneUI(sceneId);
        this._stopAudio();

        this.viewer.on('load', () => {
            const transitionTime = performance.now() - transitionStart;
            this._reportMetric('transition_time_ms', transitionTime);
        });
    }

    /**
     * Navigate to a scene by slug
     */
    goToScene(sceneId) {
        if (sceneId && this.config.scenes[sceneId]) {
            this.viewer.loadScene(sceneId);
        }
    }

    /**
     * Toggle fullscreen
     */
    toggleFullscreen() {
        const el = document.getElementById('tour-wrapper');
        if (!document.fullscreenElement) {
            el.requestFullscreen().catch(err => console.warn('Fullscreen error:', err));
        } else {
            document.exitFullscreen();
        }
    }

    /**
     * Toggle auto-rotate
     */
    toggleAutoRotate() {
        const config = this.viewer.getConfig();
        if (config.autoRotate) {
            this.viewer.stopAutoRotate();
        } else {
            this.viewer.startAutoRotate(3);
        }
        return !config.autoRotate;
    }

    /**
     * Reset to initial view
     */
    resetView() {
        const scene = this.config.scenes[this.currentSceneId];
        if (scene) {
            this.viewer.lookAt(scene.pitch, scene.yaw, scene.hfov, 700);
        }
    }

    /**
     * Zoom in/out
     */
    zoom(delta) {
        const current = this.viewer.getHfov();
        const newHfov = Math.max(30, Math.min(120, current - delta));
        this.viewer.setHfov(newHfov, 200);
    }

    /**
     * Open hotspot info panel
     */
    _onHotspotClick(hotspot) {
        const event = new CustomEvent('hotspot:open', { detail: hotspot });
        window.dispatchEvent(event);
    }

    /**
     * Update the scene UI (name bar, minimap highlight, list)
     */
    _updateSceneUI(sceneId) {
        const scene = this.config.scenes[sceneId];
        if (!scene) return;

        // Update scene name in bar
        const nameEl = document.getElementById('current-scene-name');
        if (nameEl) nameEl.textContent = scene.title;

        // Update scene list highlight
        document.querySelectorAll('[data-scene-id]').forEach(el => {
            el.classList.toggle('active', el.dataset.sceneId === sceneId);
        });

        // Dispatch event for Alpine.js components
        window.dispatchEvent(new CustomEvent('scene:changed', { detail: { sceneId, scene } }));
    }

    /**
     * Handle audio narration for a scene
     */
    _handleAudio(sceneId) {
        const scene = this.config.scenes[sceneId];
        if (scene?.audio) {
            window.dispatchEvent(new CustomEvent('audio:available', { detail: { url: scene.audio, sceneId } }));
        } else {
            window.dispatchEvent(new CustomEvent('audio:unavailable'));
        }
    }

    toggleAudio(url) {
        if (this.isAudioPlaying && this.currentAudio) {
            this._stopAudio();
            return false;
        }
        this.currentAudio = new Audio(url);
        this.currentAudio.play().catch(e => console.warn('Audio play failed:', e));
        this.isAudioPlaying = true;
        this.currentAudio.addEventListener('ended', () => {
            this.isAudioPlaying = false;
            window.dispatchEvent(new CustomEvent('audio:ended'));
        });
        return true;
    }

    _stopAudio() {
        if (this.currentAudio) {
            this.currentAudio.pause();
            this.currentAudio = null;
        }
        this.isAudioPlaying = false;
        window.dispatchEvent(new CustomEvent('audio:stopped'));
    }

    /**
     * Preload images for adjacent scenes
     */
    _preloadAdjacentScenes(sceneId) {
        const scene = this.config.scenes[sceneId];
        if (!scene) return;
        scene.hotspots
            .filter(h => h.type === 'scene' && h.sceneId)
            .forEach(h => {
                const adjacent = this.config.scenes[h.sceneId];
                if (adjacent?.panorama) {
                    const img = new Image();
                    img.src = adjacent.panorama;
                }
            });
    }

    /**
     * FPS monitor
     */
    _startFpsMonitor() {
        let lastTime = performance.now();
        let frames = 0;

        const measure = () => {
            frames++;
            const now = performance.now();
            if (now - lastTime >= 1000) {
                const fps = Math.round((frames * 1000) / (now - lastTime));
                this.fpsReadings.push(fps);
                frames = 0;
                lastTime = now;

                // Report to UI
                const fpsEl = document.getElementById('fps-counter');
                if (fpsEl) fpsEl.textContent = fps + ' FPS';
            }
            this._fpsRaf = requestAnimationFrame(measure);
        };

        this._fpsRaf = requestAnimationFrame(measure);
    }

    _getAvgFps() {
        if (!this.fpsReadings.length) return null;
        return Math.round(this.fpsReadings.reduce((a, b) => a + b, 0) / this.fpsReadings.length);
    }

    /**
     * Keyboard shortcuts
     */
    _bindKeyboard() {
        document.addEventListener('keydown', (e) => {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
            switch (e.key) {
                case 'f': case 'F': this.toggleFullscreen(); break;
                case 'r': case 'R': this.resetView(); break;
                case '+': case '=': this.zoom(10); break;
                case '-': this.zoom(-10); break;
                case 'Escape': this._closeAllPanels(); break;
            }
        });
    }

    _closeAllPanels() {
        window.dispatchEvent(new CustomEvent('panels:close-all'));
    }

    _reportMetric(key, value) {
        // Store locally for session end report
        this[`_metric_${key}`] = value;
    }

    /**
     * Analytics
     */
    async _startAnalyticsSession() {
        try {
            const res = await fetch('/api/v1/analytics/session/start', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                body: JSON.stringify({ attraction_id: this.attractionId }),
            });
            const data = await res.json();
            this.sessionUuid = data.session_uuid;
            this.sessionId = data.session_id;
        } catch (e) {
            console.warn('Analytics session start failed:', e);
        }
    }

    async _endAnalyticsSession() {
        if (!this.sessionUuid) return;
        try {
            navigator.sendBeacon('/api/v1/analytics/session/end', JSON.stringify({
                session_uuid: this.sessionUuid,
                avg_fps: this._getAvgFps(),
                load_time_ms: this._metric_load_time_ms ?? null,
                transition_time_ms: this._metric_transition_time_ms ?? null,
                scenes_visited: this.scenesVisited.size,
                completed: this.scenesVisited.size === Object.keys(this.config.scenes).length,
            }));
        } catch (e) {
            // Beacon failures are acceptable
        }
    }

    async _recordSceneView(sceneId) {
        if (!this.sessionId) return;
        try {
            await fetch('/api/v1/analytics/scene-view', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                body: JSON.stringify({ tour_session_id: this.sessionId, scene_id: sceneId }),
            });
        } catch (e) {}
    }
}

window.VirtualTourEngine = VirtualTourEngine;
