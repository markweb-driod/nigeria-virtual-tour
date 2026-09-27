"use client";

import { useEffect, useRef, useState } from "react";
import Link from "next/link";
import {
  Compass,
  ArrowLeft,
  Plus,
  Trash2,
  Navigation,
  Info,
  Check,
  X,
  Layers,
  MapPin,
} from "lucide-react";

interface Props {
  scene: any;
  availableScenes: any[];
}

export default function HotspotEditorClient({ scene, availableScenes }: Props) {
  const [hotspots, setHotspots] = useState<any[]>(scene.hotspots || []);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [clickedCoords, setClickedCoords] = useState<{ yaw: number; pitch: number }>({
    yaw: 0,
    pitch: 0,
  });

  // Modal Form State
  const [label, setLabel] = useState("");
  const [type, setType] = useState<"scene" | "info" | "media">("scene");
  const [targetSceneId, setTargetSceneId] = useState("");
  const [description, setDescription] = useState("");
  const [saving, setSaving] = useState(false);

  const viewerRef = useRef<any>(null);
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    // 1. Ensure Pannellum CDN
    const cssId = "pannellum-css";
    if (!document.getElementById(cssId)) {
      const link = document.createElement("link");
      link.id = cssId;
      link.rel = "stylesheet";
      link.href = "/pannellum/pannellum.css";
      document.head.appendChild(link);
    }

    const scriptId = "pannellum-js";
    let script = document.getElementById(scriptId) as HTMLScriptElement;
    if (!script) {
      script = document.createElement("script");
      script.id = scriptId;
      script.src = "/pannellum/pannellum.js";
      script.onload = () => initEditor();
      document.body.appendChild(script);
    } else {
      initEditor();
    }

    function initEditor() {
      if (!window.pannellum || !containerRef.current) return;

      const panoramaUrl = scene.panorama_path.startsWith("http")
        ? scene.panorama_path
        : `/${scene.panorama_path.replace(/^\/?(storage\/)?/, "")}`;

      try {
        viewerRef.current = window.pannellum.viewer("editor-panorama-container", {
          type: "equirectangular",
          panorama: panoramaUrl,
          yaw: scene.initial_yaw || 0,
          pitch: scene.initial_pitch || 0,
          hfov: scene.initial_hfov || 100,
          autoLoad: true,
          showControls: true,
          hotSpots: hotspots.map((hs) => ({
            pitch: hs.pitch,
            yaw: hs.yaw,
            type: "info",
            text: hs.label,
            cssClass: hs.type === "scene" ? "custom-hotspot-scene" : "custom-hotspot-info",
          })),
        });

        // 2. Click on panorama captures coordinates
        const el = document.getElementById("editor-panorama-container");
        if (el) {
          el.addEventListener("mouseup", (e) => {
            if (!viewerRef.current) return;
            const coords = viewerRef.current.mouseEventToCoords(e);
            if (coords) {
              const pitch = Math.round(coords[0] * 100) / 100;
              const yaw = Math.round(coords[1] * 100) / 100;
              setClickedCoords({ yaw, pitch });
              setIsModalOpen(true);
            }
          });
        }
      } catch (err) {
        console.error("Editor initialization error:", err);
      }
    }

    return () => {
      if (viewerRef.current) {
        try {
          viewerRef.current.destroy();
        } catch (e) {}
      }
    };
  }, [scene]);

  const handleCreateHotspot = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!label.trim()) return;

    setSaving(true);
    try {
      const res = await fetch(`/api/v1/scenes/${scene.id}/hotspots`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          type,
          yaw: clickedCoords.yaw,
          pitch: clickedCoords.pitch,
          label,
          description,
          target_scene_id: type === "scene" ? targetSceneId : null,
        }),
      });

      const data = await res.json();
      if (data.success && data.hotspot) {
        setHotspots([...hotspots, data.hotspot]);

        // Add to Pannellum viewer dynamically
        if (viewerRef.current) {
          viewerRef.current.addHotSpot({
            pitch: clickedCoords.pitch,
            yaw: clickedCoords.yaw,
            type: "info",
            text: label,
            cssClass: type === "scene" ? "custom-hotspot-scene" : "custom-hotspot-info",
          });
        }

        setIsModalOpen(false);
        setLabel("");
        setDescription("");
      }
    } catch (err) {
      console.error("Failed to save hotspot:", err);
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteHotspot = async (hotspotId: string) => {
    if (!confirm("Are you sure you want to remove this navigation marker?")) return;

    try {
      const res = await fetch(`/api/v1/scenes/${scene.id}/hotspots?hotspot_id=${hotspotId}`, {
        method: "DELETE",
      });
      const data = await res.json();
      if (data.success) {
        setHotspots(hotspots.filter((h) => h.id.toString() !== hotspotId.toString()));
        if (viewerRef.current) {
          viewerRef.current.removeHotSpot(hotspotId);
        }
      }
    } catch (err) {
      console.error("Failed to delete hotspot:", err);
    }
  };

  return (
    <div className="max-w-7xl mx-auto space-y-6">
      {/* Top Bar */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-white/10 pb-5">
        <div className="flex items-center gap-3">
          <Link
            href="/admin"
            className="p-2.5 rounded-xl glass-panel hover:bg-white/10 text-stone-300 hover:text-white"
          >
            <ArrowLeft className="w-4 h-4" />
          </Link>
          <div>
            <span className="text-[10px] uppercase font-bold text-gold-400 tracking-wider block">
              {scene.attraction?.name} • Visual Hotspot Editor
            </span>
            <h1 className="font-heading font-black text-2xl text-white">
              {scene.name}
            </h1>
          </div>
        </div>

        <div className="flex items-center gap-2">
          <span className="px-3 py-1 rounded-full text-xs font-mono bg-white/5 border border-white/10 text-stone-300">
            {hotspots.length} Markers Defined
          </span>
          <Link
            href={`/tour/${scene.attraction?.slug}`}
            className="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gold-400 text-obsidian shadow-glow-gold hover:bg-gold-300 transition-colors"
          >
            Live Preview
          </Link>
        </div>
      </div>

      {/* Editor Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {/* Left: 360 Canvas */}
        <div className="lg:col-span-8 space-y-3">
          <div className="relative h-[550px] rounded-3xl overflow-hidden glass-panel-elevated border border-gold-500/30 shadow-2xl">
            <div id="editor-panorama-container" ref={containerRef} className="w-full h-full cursor-crosshair" />

            {/* Instruction Banner */}
            <div className="absolute top-4 left-4 right-4 pointer-events-none flex justify-center">
              <div className="px-4 py-2 rounded-full bg-obsidian/85 border border-gold-500/40 text-xs font-medium text-stone-200 backdrop-blur-md shadow-xl flex items-center gap-2">
                <Compass className="w-4 h-4 text-gold-400 animate-spin-slow" />
                <span>Click anywhere on the panorama canvas to automatically place a hotspot</span>
              </div>
            </div>
          </div>
        </div>

        {/* Right: Hotspots List & Scene Selector */}
        <div className="lg:col-span-4 space-y-6">
          <div className="glass-panel p-6 rounded-3xl border border-white/10 space-y-4">
            <div className="flex items-center justify-between">
              <h3 className="font-heading font-bold text-sm text-white uppercase tracking-wider">
                Configured Markers ({hotspots.length})
              </h3>
            </div>

            <div className="space-y-2.5 max-h-[460px] overflow-y-auto pr-1">
              {hotspots.map((hs) => (
                <div
                  key={hs.id}
                  className="p-3.5 rounded-2xl bg-white/5 border border-white/5 hover:border-white/10 transition-all flex items-start justify-between gap-3"
                >
                  <div className="space-y-1 min-w-0">
                    <div className="flex items-center gap-2">
                      <span
                        className={`w-2 h-2 rounded-full ${
                          hs.type === "scene" ? "bg-emerald-400" : "bg-sky-400"
                        }`}
                      />
                      <span className="font-heading font-bold text-xs text-white truncate block">
                        {hs.label}
                      </span>
                    </div>

                    <div className="flex items-center gap-2 text-[10px] font-mono text-stone-400">
                      <span>Yaw: {hs.yaw}°</span>
                      <span>Pitch: {hs.pitch}°</span>
                    </div>

                    {hs.type === "scene" && hs.target_scene && (
                      <span className="text-[10px] text-emerald-400 block">
                        → Leads to: {hs.target_scene.name}
                      </span>
                    )}
                  </div>

                  <button
                    onClick={() => handleDeleteHotspot(hs.id.toString())}
                    className="p-1.5 rounded-lg text-stone-500 hover:text-red-400 hover:bg-red-950/40 transition-colors"
                    title="Remove Hotspot"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                  </button>
                </div>
              ))}

              {hotspots.length === 0 && (
                <div className="text-center py-12 text-xs text-stone-400">
                  No markers created yet. Click on the panorama to create your first navigation or info marker.
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* ===== CREATE HOTSPOT POPUP MODAL ===== */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-black/75 backdrop-blur-md z-50 flex items-center justify-center p-4">
          <div className="relative w-full max-w-md glass-panel-elevated p-8 rounded-3xl border border-gold-500/40 shadow-glow-gold space-y-6">
            <div className="flex items-center justify-between">
              <div>
                <span className="text-[10px] uppercase font-bold text-gold-400 tracking-wider">
                  Automated Coordinate Capture
                </span>
                <h3 className="font-heading font-black text-xl text-white">
                  Create Hotspot Marker
                </h3>
              </div>
              <button
                onClick={() => setIsModalOpen(false)}
                className="p-1.5 rounded-xl hover:bg-white/10 text-stone-400 hover:text-white"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {/* Coordinates Badge */}
            <div className="grid grid-cols-2 gap-3 p-3 rounded-2xl bg-white/5 border border-white/5 text-center text-xs font-mono">
              <div>
                <span className="text-[10px] text-stone-400 block uppercase">Yaw (Horizontal)</span>
                <span className="text-white font-bold">{clickedCoords.yaw}°</span>
              </div>
              <div>
                <span className="text-[10px] text-stone-400 block uppercase">Pitch (Vertical)</span>
                <span className="text-white font-bold">{clickedCoords.pitch}°</span>
              </div>
            </div>

            <form onSubmit={handleCreateHotspot} className="space-y-4 text-xs">
              {/* Hotspot Type */}
              <div>
                <label className="block text-stone-300 font-semibold mb-1.5">Marker Type</label>
                <div className="grid grid-cols-2 gap-2">
                  <button
                    type="button"
                    onClick={() => setType("scene")}
                    className={`py-2.5 rounded-xl font-bold uppercase tracking-wider text-[11px] border transition-all ${
                      type === "scene"
                        ? "bg-emerald-950/80 border-emerald-400 text-emerald-300"
                        : "bg-white/5 border-white/10 text-stone-400"
                    }`}
                  >
                    Scene Navigation
                  </button>
                  <button
                    type="button"
                    onClick={() => setType("info")}
                    className={`py-2.5 rounded-xl font-bold uppercase tracking-wider text-[11px] border transition-all ${
                      type === "info"
                        ? "bg-sky-950/80 border-sky-400 text-sky-300"
                        : "bg-white/5 border-white/10 text-stone-400"
                    }`}
                  >
                    Information Note
                  </button>
                </div>
              </div>

              {/* Label */}
              <div>
                <label className="block text-stone-300 font-semibold mb-1.5">Label *</label>
                <input
                  type="text"
                  value={label}
                  onChange={(e) => setLabel(e.target.value)}
                  placeholder={type === "scene" ? "e.g. Continue to Summit Peak →" : "e.g. History of the Sacred Tree"}
                  required
                  className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-gold-500/50"
                />
              </div>

              {/* Target Scene (for scene navigation) */}
              {type === "scene" && (
                <div>
                  <label className="block text-stone-300 font-semibold mb-1.5">Target Scene</label>
                  <select
                    value={targetSceneId}
                    onChange={(e) => setTargetSceneId(e.target.value)}
                    required
                    className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-3 py-2.5 text-stone-200 focus:outline-none focus:border-gold-500/50"
                  >
                    <option value="">Select target scene node...</option>
                    {availableScenes
                      .filter((s) => s.id !== scene.id)
                      .map((s) => (
                        <option key={s.id} value={s.id}>
                          {s.name}
                        </option>
                      ))}
                  </select>
                </div>
              )}

              {/* Description (for info marker) */}
              {type === "info" && (
                <div>
                  <label className="block text-stone-300 font-semibold mb-1.5">Description Note</label>
                  <textarea
                    rows={3}
                    value={description}
                    onChange={(e) => setDescription(e.target.value)}
                    placeholder="Enter the historical fact or note that tourists will see when clicking this marker..."
                    className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-4 py-2.5 text-white focus:outline-none focus:border-gold-500/50"
                  />
                </div>
              )}

              <div className="pt-2 flex items-center justify-end gap-3">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2.5 rounded-xl hover:bg-white/10 text-stone-400 hover:text-white"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  className="px-6 py-2.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-gold-400 hover:bg-gold-300 text-obsidian shadow-glow-gold transition-all"
                >
                  {saving ? "Saving..." : "Create Hotspot"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
