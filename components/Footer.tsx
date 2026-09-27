import Link from "next/link";
import { Compass, Shield, Heart, MapPin, Sparkles } from "lucide-react";

export default function Footer() {
  return (
    <footer className="relative bg-obsidian-surface border-t border-white/5 pt-16 pb-12 overflow-hidden">
      {/* Ambient background glow */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-96 h-24 bg-forest-500/10 blur-3xl pointer-events-none" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
          {/* Brand Info */}
          <div className="md:col-span-2 space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-9 h-9 rounded-xl bg-forest-600 border border-gold-500/30 flex items-center justify-center">
                <Compass className="w-5 h-5 text-gold-400" />
              </div>
              <span className="font-heading font-black text-xl text-white tracking-tight">
                NIGERIA<span className="text-gold-400 ml-1">360°</span>
              </span>
            </div>
            <p className="text-sm text-stone-400 max-w-md leading-relaxed">
              An immersive WebGL virtual tour system dedicated to documenting, preserving, and
              celebrating Nigerian tourist destinations and cultural monuments through high-definition
              360° panoramic imaging.
            </p>
            <div className="flex items-center gap-4 text-xs text-stone-400">
              <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-forest-900/60 border border-forest-500/30 text-emerald-300">
                <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                Browser-Based WebGL
              </span>
              <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-900/40 border border-gold-500/30 text-gold-300">
                <Sparkles className="w-3 h-3" />
                High Dynamic Range Panoramas
              </span>
            </div>
          </div>

          {/* Featured Tours */}
          <div>
            <h4 className="font-heading font-bold text-sm text-white uppercase tracking-wider mb-4">
              Featured 360° Tours
            </h4>
            <ul className="space-y-2.5 text-sm text-stone-400">
              <li>
                <Link href="/tour/olumo-rock" className="hover:text-gold-300 transition-colors">
                  Olumo Rock (5 Scenes)
                </Link>
              </li>
              <li>
                <Link href="/tour/zuma-rock" className="hover:text-gold-300 transition-colors">
                  Zuma Rock Monolith
                </Link>
              </li>
              <li>
                <Link href="/tour/yankari-game-reserve" className="hover:text-gold-300 transition-colors">
                  Yankari & Wikki Warm Springs
                </Link>
              </li>
              <li>
                <Link href="/tour/obudu-mountain-resort" className="hover:text-gold-300 transition-colors">
                  Obudu Mountain Cloud Plateau
                </Link>
              </li>
              <li>
                <Link href="/tour/lekki-conservation-centre" className="hover:text-gold-300 transition-colors">
                  Lekki Canopy Walkway
                </Link>
              </li>
            </ul>
          </div>

          {/* Academic & Platform */}
          <div>
            <h4 className="font-heading font-bold text-sm text-white uppercase tracking-wider mb-4">
              Platform & Research
            </h4>
            <ul className="space-y-2.5 text-sm text-stone-400">
              <li>
                <Link href="/explore" className="hover:text-gold-300 transition-colors">
                  Destination Explorer
                </Link>
              </li>
              <li>
                <Link href="/map" className="hover:text-gold-300 transition-colors">
                  Interactive National Map
                </Link>
              </li>
              <li>
                <Link href="/admin" className="hover:text-gold-300 transition-colors flex items-center gap-1.5">
                  <Shield className="w-3.5 h-3.5 text-gold-400" />
                  Admin Hotspot CMS
                </Link>
              </li>
              <li>
                <span className="text-xs text-stone-400 block pt-2">
                  Academic Scope: 360° Panorama Tourism System (Excluding hotel & flight reservations)
                </span>
              </li>
            </ul>
          </div>
        </div>

        <div className="border-t border-white/5 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-400">
          <p>© {new Date().getFullYear()} Virtual Tour System for Nigerian Tourist Attractions.</p>
          <p className="flex items-center gap-1.5">
            Engineered with Next.js, WebGL & Pannellum
          </p>
        </div>
      </div>
    </footer>
  );
}
