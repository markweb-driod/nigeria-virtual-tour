"use client";

import { useEffect, useRef, useState } from "react";
import Link from "next/link";
import { Compass, MapPin, Play, Layers } from "lucide-react";

interface Props {
  attractions: any[];
}

export default function MapClient({ attractions }: Props) {
  const mapRef = useRef<any>(null);
  const containerRef = useRef<HTMLDivElement>(null);
  const [selectedAttraction, setSelectedAttraction] = useState<any>(null);

  useEffect(() => {
    // 1. Ensure Leaflet CSS
    const cssId = "leaflet-css";
    if (!document.getElementById(cssId)) {
      const link = document.createElement("link");
      link.id = cssId;
      link.rel = "stylesheet";
      link.href = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css";
      document.head.appendChild(link);
    }

    // 2. Ensure Leaflet Script
    const scriptId = "leaflet-js";
    let script = document.getElementById(scriptId) as HTMLScriptElement;
    if (!script) {
      script = document.createElement("script");
      script.id = scriptId;
      script.src = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js";
      script.onload = () => initMap();
      document.body.appendChild(script);
    } else {
      initMap();
    }

    function initMap() {
      if (!(window as any).L || !containerRef.current || mapRef.current) return;
      const L = (window as any).L;

      // Center on Nigeria (lat 9.0820, lng 8.6753)
      const map = L.map("nigeria-map", {
        center: [9.082, 8.6753],
        zoom: 6,
        minZoom: 5,
        maxZoom: 16,
      });
      mapRef.current = map;

      // Dark style tile layer
      L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png",
        {
          attribution: '&copy; <a href="https://carto.com/">CARTO</a> OpenStreetMap',
          subdomains: "abcd",
          maxZoom: 19,
        }
      ).addTo(map);

      // Add pins for attractions
      attractions.forEach((attraction) => {
        const lat = parseFloat(attraction.latitude);
        const lng = parseFloat(attraction.longitude);
        if (isNaN(lat) || isNaN(lng)) return;

        // Custom HTML Marker Icon
        const customIcon = L.divIcon({
          className: "custom-map-pin",
          html: `<div style="
            background: #0B3D2E;
            color: #D4A017;
            border: 2px solid #D4A017;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
          ">📍</div>`,
          iconSize: [32, 32],
          iconAnchor: [16, 32],
        });

        const marker = L.marker([lat, lng], { icon: customIcon }).addTo(map);

        marker.on("click", () => {
          setSelectedAttraction(attraction);
        });
      });
    }

    return () => {
      if (mapRef.current) {
        mapRef.current.remove();
        mapRef.current = null;
      }
    };
  }, [attractions]);

  return (
    <div className="relative flex-1 w-full h-[calc(100vh-80px)] overflow-hidden">
      {/* Map Container */}
      <div id="nigeria-map" ref={containerRef} className="w-full h-full z-10" />

      {/* Floating Header Card */}
      <div className="absolute top-6 left-6 z-20 pointer-events-auto">
        <div className="glass-panel-elevated p-4 rounded-2xl border border-white/10 shadow-2xl max-w-xs space-y-1">
          <div className="flex items-center gap-2">
            <Compass className="w-4 h-4 text-gold-400" />
            <h3 className="font-heading font-black text-sm text-white">
              National Tourism Map
            </h3>
          </div>
          <p className="text-[11px] text-stone-300">
            Click on any marker across Nigeria to view destination information and launch its 360° virtual tour.
          </p>
        </div>
      </div>

      {/* Selected Attraction Popup Drawer */}
      {selectedAttraction && (
        <div className="absolute bottom-8 left-6 right-6 sm:left-auto sm:right-8 sm:w-96 z-20 pointer-events-auto">
          <div className="glass-panel-elevated p-6 rounded-3xl border border-gold-500/40 shadow-glow-gold space-y-4">
            <div className="flex items-start justify-between">
              <div>
                <span className="text-[10px] uppercase font-bold text-gold-400 tracking-wider">
                  {selectedAttraction.state?.name} State
                </span>
                <h4 className="font-heading font-black text-xl text-white">
                  {selectedAttraction.name}
                </h4>
              </div>
              <button
                onClick={() => setSelectedAttraction(null)}
                className="text-stone-400 hover:text-white p-1 text-xs"
              >
                ✕
              </button>
            </div>

            <p className="text-xs text-stone-300 line-clamp-3 leading-relaxed">
              {selectedAttraction.short_description}
            </p>

            <div className="flex items-center gap-3 pt-2">
              <Link
                href={`/tour/${selectedAttraction.slug}`}
                className="flex-1 inline-flex justify-center items-center gap-2 py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-gold-400 text-obsidian shadow-glow-gold hover:bg-gold-300 transition-all"
              >
                <Play className="w-3.5 h-3.5 fill-obsidian" />
                <span>Launch 360° Tour</span>
              </Link>
              <Link
                href={`/attractions/${selectedAttraction.slug}`}
                className="px-4 py-3 rounded-xl text-xs font-semibold text-stone-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10"
              >
                Dossier
              </Link>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
