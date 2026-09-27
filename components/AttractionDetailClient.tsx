"use client";

import { useState } from "react";
import Link from "next/link";
import { getAssetUrl } from "@/lib/utils";
import {
  Compass,
  Play,
  MapPin,
  Calendar,
  Ticket,
  Volume2,
  VolumeX,
  Share2,
  Sparkles,
  ArrowRight,
  Eye,
  Info,
  Layers,
  CheckCircle,
} from "lucide-react";

interface Props {
  attraction: any;
  relatedAttractions: any[];
}

export default function AttractionDetailClient({
  attraction,
  relatedAttractions,
}: Props) {
  const [activeTab, setActiveTab] = useState<"about" | "history" | "facts" | "scenes" | "gallery">("about");
  const [isPlayingAudio, setIsPlayingAudio] = useState(false);
  const [copied, setCopied] = useState(false);

  const toggleAudio = () => {
    const audioEl = document.getElementById("attraction-audio") as HTMLAudioElement;
    if (audioEl) {
      if (isPlayingAudio) {
        audioEl.pause();
        setIsPlayingAudio(false);
      } else {
        audioEl.play().catch(console.warn);
        setIsPlayingAudio(true);
      }
    }
  };

  const handleShare = () => {
    if (navigator.share) {
      navigator.share({
        title: attraction.name,
        text: attraction.short_description,
        url: window.location.href,
      }).catch(console.warn);
    } else {
      navigator.clipboard.writeText(window.location.href);
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    }
  };

  return (
    <div className="relative pb-24">
      {/* ===== HERO BANNER ===== */}
      <div className="relative min-h-[60vh] flex items-end pt-36 sm:pt-40 pb-20 px-4 sm:px-6 lg:px-8 bg-obsidian overflow-hidden">
        {/* Real photographic background with dark gradient overlay */}
        <img
          src={getAssetUrl(attraction.hero_image || attraction.thumbnail)}
          alt={attraction.name}
          className="absolute inset-0 w-full h-full object-cover object-center"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/85 to-obsidian/60" />

        {/* Ambient Glows */}
        <div className="absolute top-1/4 left-1/3 w-[500px] h-[300px] bg-forest-600/20 blur-3xl pointer-events-none rounded-full" />
        <div className="absolute bottom-0 right-10 w-96 h-48 bg-gold-500/10 blur-3xl pointer-events-none rounded-full" />

        <div className="relative z-10 max-w-7xl mx-auto w-full">
          {/* Breadcrumb & Badges */}
          <div className="flex flex-wrap items-center gap-2 mb-4">
            <Link
              href="/explore"
              className="text-xs text-stone-400 hover:text-white transition-colors"
            >
              Destinations
            </Link>
            <span className="text-stone-600">/</span>
            <span className="text-xs text-gold-400">{attraction.state?.name}</span>

            <div className="ml-auto flex items-center gap-2">
              <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gold-500/20 text-gold-300 border border-gold-500/30">
                {attraction.category?.name}
              </span>
              {attraction.tour_available && (
                <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-300 border border-emerald-500/40 flex items-center gap-1.5">
                  <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" />
                  360° Tour Ready
                </span>
              )}
            </div>
          </div>

          {/* Title & Location */}
          <h1 className="font-heading font-black text-4xl sm:text-6xl lg:text-7xl text-white tracking-tight mb-3">
            {attraction.name}
          </h1>

          <div className="flex flex-wrap items-center gap-4 text-sm text-stone-300 mb-8">
            <span className="flex items-center gap-1.5">
              <MapPin className="w-4 h-4 text-gold-400" />
              {attraction.address || `${attraction.lga || ""}, ${attraction.state?.name}, Nigeria`}
            </span>
            {attraction.visiting_hours && (
              <span className="flex items-center gap-1.5 text-stone-400">
                <Calendar className="w-4 h-4 text-stone-500" />
                {attraction.visiting_hours}
              </span>
            )}
            {attraction.entry_fee && (
              <span className="flex items-center gap-1.5 text-stone-400">
                <Ticket className="w-4 h-4 text-stone-500" />
                {attraction.entry_fee}
              </span>
            )}
          </div>

          {/* Primary Action Row */}
          <div className="flex flex-wrap items-center gap-4">
            {attraction.tour_available && (
              <Link
                href={`/tour/${attraction.slug}`}
                className="inline-flex items-center gap-3 px-8 py-4 rounded-2xl font-heading font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-gold-400 via-gold-500 to-gold-600 hover:from-gold-300 hover:to-gold-500 text-obsidian shadow-glow-gold hover:scale-105 active:scale-95 transition-all duration-200"
              >
                <Play className="w-4 h-4 fill-obsidian" />
                <span>Enter 360° Panoramic Environment</span>
              </Link>
            )}

            {attraction.audio && (
              <>
                <audio id="attraction-audio" src={attraction.audio} />
                <button
                  onClick={toggleAudio}
                  className="inline-flex items-center gap-2 px-5 py-3.5 rounded-2xl text-xs font-bold uppercase tracking-wider text-stone-200 bg-white/5 hover:bg-white/10 border border-white/10 transition-colors"
                >
                  {isPlayingAudio ? (
                    <>
                      <VolumeX className="w-4 h-4 text-gold-400" />
                      <span>Pause Narration</span>
                    </>
                  ) : (
                    <>
                      <Volume2 className="w-4 h-4 text-gold-400" />
                      <span>Audio Guide</span>
                    </>
                  )}
                </button>
              </>
            )}

            <button
              onClick={handleShare}
              className="p-3.5 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-stone-300 hover:text-white transition-colors"
              title="Share destination"
            >
              <Share2 className="w-4 h-4" />
            </button>
            {copied && <span className="text-xs text-gold-400 font-mono">Link Copied!</span>}
          </div>
        </div>
      </div>

      {/* ===== TABS & MAIN DOSSIER ===== */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
          {/* Main Dossier Column */}
          <div className="lg:col-span-8 space-y-8">
            {/* Tabs Header */}
            <div className="flex items-center gap-2 border-b border-white/10 pb-3 overflow-x-auto text-xs font-heading font-bold uppercase tracking-wider">
              {[
                { id: "about", label: "Overview" },
                { id: "history", label: "Historical Heritage" },
                { id: "facts", label: "Facts & Folklore" },
                { id: "scenes", label: `360° Scenes (${attraction.scenes?.length || 0})` },
                { id: "gallery", label: "Gallery" },
              ].map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id as any)}
                  className={`px-4 py-2.5 rounded-xl transition-all whitespace-nowrap ${
                    activeTab === tab.id
                      ? "bg-forest-600 text-white shadow-md border border-forest-400/40"
                      : "text-stone-400 hover:text-white hover:bg-white/5"
                  }`}
                >
                  {tab.label}
                </button>
              ))}
            </div>

            {/* Tab: Overview */}
            {activeTab === "about" && (
              <div className="space-y-6">
                <div className="glass-panel p-8 rounded-3xl border border-white/10 space-y-4">
                  <h3 className="font-heading font-black text-2xl text-white">
                    About {attraction.name}
                  </h3>
                  <div className="prose prose-invert prose-stone max-w-none text-stone-300 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                    {attraction.description}
                  </div>
                </div>

                {/* Quick Highlights Grid */}
                <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div className="glass-panel p-5 rounded-2xl border border-white/5">
                    <span className="text-[10px] uppercase font-bold text-gold-400 tracking-wider">
                      Region
                    </span>
                    <p className="font-heading font-bold text-base text-white mt-1">
                      {attraction.state?.region || "Federal Republic of Nigeria"}
                    </p>
                  </div>
                  <div className="glass-panel p-5 rounded-2xl border border-white/5">
                    <span className="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">
                      Tour Nodes
                    </span>
                    <p className="font-heading font-bold text-base text-white mt-1">
                      {attraction.scenes?.length || 1} Equirectangular Scenes
                    </p>
                  </div>
                  <div className="glass-panel p-5 rounded-2xl border border-white/5">
                    <span className="text-[10px] uppercase font-bold text-stone-400 tracking-wider">
                      Geographic Coords
                    </span>
                    <p className="font-mono text-xs text-stone-300 mt-1">
                      {attraction.latitude}° N, {attraction.longitude}° E
                    </p>
                  </div>
                </div>
              </div>
            )}

            {/* Tab: History */}
            {activeTab === "history" && (
              <div className="glass-panel p-8 rounded-3xl border border-white/10 space-y-4">
                <h3 className="font-heading font-black text-2xl text-white">
                  Historical Significance & Refuge
                </h3>
                {attraction.history ? (
                  <div className="prose prose-invert prose-stone max-w-none text-stone-300 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                    {attraction.history}
                  </div>
                ) : (
                  <p className="text-stone-400 text-sm">
                    Historical documentation is currently being compiled by the research archive.
                  </p>
                )}
              </div>
            )}

            {/* Tab: Facts */}
            {activeTab === "facts" && (
              <div className="glass-panel p-8 rounded-3xl border border-white/10 space-y-4">
                <h3 className="font-heading font-black text-2xl text-white">
                  Interesting Facts & Folklore
                </h3>
                {attraction.interesting_facts ? (
                  <div className="prose prose-invert prose-stone max-w-none text-stone-300 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                    {attraction.interesting_facts}
                  </div>
                ) : (
                  <p className="text-stone-400 text-sm">
                    Folkloric and geological facts are currently being documented.
                  </p>
                )}
              </div>
            )}

            {/* Tab: Scenes List */}
            {activeTab === "scenes" && (
              <div className="space-y-4">
                <h3 className="font-heading font-black text-2xl text-white">
                  Available 360° Panoramic Scenes
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {attraction.scenes?.map((scene: any) => (
                    <div
                      key={scene.id}
                      className="glass-panel p-5 rounded-2xl border border-white/10 hover:border-gold-500/40 transition-all flex flex-col justify-between space-y-3 overflow-hidden group"
                    >
                      <div>
                        <div className="relative h-32 rounded-xl overflow-hidden mb-3 bg-obsidian">
                          <img
                            src={getAssetUrl(scene.thumbnail || attraction.thumbnail)}
                            alt={scene.name}
                            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            onError={(e: any) => { e.currentTarget.src = "/images/placeholder.webp"; }}
                          />
                          <div className="absolute inset-0 bg-gradient-to-t from-obsidian/80 via-transparent to-transparent" />
                          <div className="absolute top-2 left-2 flex items-center gap-1.5">
                            <span className="text-[10px] font-mono px-2 py-0.5 rounded bg-obsidian/80 backdrop-blur-md text-stone-300 uppercase border border-white/10">
                              Node {scene.sort_order}
                            </span>
                            {scene.is_default && (
                              <span className="px-2 py-0.5 rounded text-[10px] font-bold bg-gold-500/30 backdrop-blur-md text-gold-300 border border-gold-500/40">
                                Start Scene
                              </span>
                            )}
                          </div>
                        </div>

                        <h4 className="font-heading font-bold text-base text-white group-hover:text-gold-300 transition-colors">
                          {scene.name}
                        </h4>
                        <p className="text-xs text-stone-300 mt-1 line-clamp-2">
                          {scene.description || "Equirectangular WebGL panorama perspective."}
                        </p>
                      </div>

                      <Link
                        href={`/tour/${attraction.slug}`}
                        className="inline-flex items-center gap-1.5 text-xs font-bold text-gold-400 hover:text-gold-300 uppercase tracking-wider pt-2"
                      >
                        <span>Explore 360° Node</span>
                        <ArrowRight className="w-3.5 h-3.5" />
                      </Link>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* Tab: Gallery */}
            {activeTab === "gallery" && (
              <div className="glass-panel p-8 rounded-3xl border border-white/10 space-y-4">
                <h3 className="font-heading font-black text-2xl text-white">
                  Destination Photographic Gallery
                </h3>
                {attraction.media_items?.length > 0 ? (
                  <div className="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    {attraction.media_items.map((media: any) => (
                      <div
                        key={media.id}
                        className="relative h-48 rounded-2xl overflow-hidden border border-white/10"
                      >
                        <img
                          src={getAssetUrl(media.path)}
                          alt={media.caption || attraction.name}
                          className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                          onError={(e: any) => { e.currentTarget.src = "/images/placeholder.webp"; }}
                        />
                      </div>
                    ))}
                  </div>
                ) : (
                  <p className="text-stone-400 text-sm">
                    High-resolution still captures are integrated inside the 360° panorama hotspots.
                  </p>
                )}
              </div>
            )}
          </div>

          {/* Sidebar Column */}
          <div className="lg:col-span-4 space-y-6">
            {/* 360 Tour Quick Card */}
            {attraction.tour_available && (
              <div className="glass-panel-elevated p-6 rounded-3xl border border-gold-500/30 space-y-4">
                <div className="w-12 h-12 rounded-2xl bg-gold-500/20 border border-gold-500/40 flex items-center justify-center">
                  <Compass className="w-6 h-6 text-gold-400 animate-spin-slow" />
                </div>
                <div>
                  <h4 className="font-heading font-bold text-lg text-white">
                    Full Virtual Tour Experience
                  </h4>
                  <p className="text-xs text-stone-300 mt-1 leading-relaxed">
                    Rotate 360 degrees, click navigation arrows to walk between locations, and access multimedia markers.
                  </p>
                </div>
                <Link
                  href={`/tour/${attraction.slug}`}
                  className="w-full inline-flex justify-center items-center gap-2 px-6 py-3.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-gold-400 hover:bg-gold-300 text-obsidian shadow-glow-gold transition-all"
                >
                  <Play className="w-4 h-4 fill-obsidian" />
                  <span>Launch 360° Experience</span>
                </Link>
              </div>
            )}

            {/* Practical Visiting Info */}
            <div className="glass-panel p-6 rounded-3xl border border-white/10 space-y-4">
              <h4 className="font-heading font-bold text-sm text-white uppercase tracking-wider">
                Practical Visitor Information
              </h4>
              <dl className="space-y-3 text-xs">
                <div>
                  <dt className="text-stone-400">Jurisdiction</dt>
                  <dd className="font-semibold text-white mt-0.5">
                    {attraction.lga ? `${attraction.lga} LGA, ` : ""}{attraction.state?.name} State
                  </dd>
                </div>
                {attraction.visiting_hours && (
                  <div>
                    <dt className="text-stone-400">Visiting Hours</dt>
                    <dd className="font-semibold text-white mt-0.5">{attraction.visiting_hours}</dd>
                  </div>
                )}
                {attraction.entry_fee && (
                  <div>
                    <dt className="text-stone-400">Entry Fees</dt>
                    <dd className="font-semibold text-white mt-0.5">{attraction.entry_fee}</dd>
                  </div>
                )}
              </dl>
            </div>

            {/* Related Attractions */}
            {relatedAttractions.length > 0 && (
              <div className="glass-panel p-6 rounded-3xl border border-white/10 space-y-4">
                <h4 className="font-heading font-bold text-sm text-white uppercase tracking-wider">
                  Similar Destinations
                </h4>
                <div className="space-y-3">
                  {relatedAttractions.map((item) => (
                    <Link
                      key={item.id}
                      href={`/attractions/${item.slug}`}
                      className="group flex items-center gap-3 p-3 rounded-2xl hover:bg-white/5 transition-colors border border-transparent hover:border-white/5"
                    >
                      <img
                        src={getAssetUrl(item.thumbnail)}
                        alt={item.name}
                        className="w-14 h-14 rounded-xl object-cover flex-shrink-0"
                        onError={(e: any) => { e.currentTarget.src = "/images/placeholder.webp"; }}
                      />
                      <div className="min-w-0 flex-1">
                        <h5 className="font-heading font-bold text-sm text-white group-hover:text-gold-300 transition-colors truncate">
                          {item.name}
                        </h5>
                        <p className="text-xs text-stone-400 mt-0.5 truncate">
                          {item.state?.name} State • {item.category?.name}
                        </p>
                      </div>
                    </Link>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
