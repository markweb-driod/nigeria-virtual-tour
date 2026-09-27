import Link from "next/link";
import { prisma } from "@/lib/prisma";
import { serializeData, getAssetUrl } from "@/lib/utils";
import {
  Compass,
  Sparkles,
  MapPin,
  Eye,
  Shield,
  Layers,
  ArrowRight,
  Play,
  RotateCcw,
  Camera,
  CheckCircle2,
} from "lucide-react";

export const revalidate = 60; // ISR cache

export default async function HomePage() {
  let attractions = [];
  let categories = [];
  let totalAttractions = 0;
  let totalScenes = 0;

  try {
    const rawAttractions = await prisma.attraction.findMany({
      where: { status: "published" },
      include: {
        category: true,
        state: true,
        scenes: {
          select: { id: true, name: true, slug: true, is_default: true },
        },
      },
      orderBy: { view_count: "desc" },
      take: 6,
    });
    attractions = serializeData(rawAttractions);

    const rawCategories = await prisma.category.findMany({
      include: {
        _count: { select: { attractions: true } },
      },
    });
    categories = serializeData(rawCategories);

    totalAttractions = await prisma.attraction.count({ where: { status: "published" } });
    totalScenes = await prisma.scene.count({ where: { status: "published" } });
  } catch (err) {
    console.error("Database query failed:", err);
  }

  const featured = attractions[0] || {
    name: "Olumo Rock Sanctuary",
    slug: "olumo-rock",
    short_description: "A monumental sacred fortress standing at 137 metres above Abeokuta.",
    state: { name: "Ogun" },
    category: { name: "Historical" },
    thumbnail: "/attractions/thumbs/olumo-thumb.jpg",
  };

  return (
    <div className="relative overflow-hidden bg-obsidian">
      {/* ===== HERO SECTION ===== */}
      <section className="relative min-h-[92vh] flex items-center justify-center pt-32 sm:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
        {/* Ambient Gradient Mesh Background */}
        <div className="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-20%,rgba(19,84,63,0.35),rgba(7,12,10,0))]" />
        <div className="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-forest-600/20 via-gold-500/10 to-transparent blur-3xl pointer-events-none rounded-full" />

        <div className="relative z-10 max-w-5xl mx-auto text-center">
          {/* Top Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-8 shadow-inner-glow">
            <span className="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping" />
            <span className="text-xs font-bold tracking-widest uppercase text-stone-200">
              Web-Based 360° Panoramic Imaging
            </span>
          </div>

          {/* Headline */}
          <h1 className="font-heading font-black text-5xl sm:text-6xl md:text-7xl lg:text-8xl tracking-tight text-white leading-[1.05] mb-6">
            Explore Nigeria <br />
            <span className="text-gold-gradient drop-shadow-md">Like Never Before</span>
          </h1>

          <p className="max-w-2xl mx-auto text-lg sm:text-xl text-stone-300 font-normal leading-relaxed mb-10">
            Step directly into Nigeria's most iconic natural monoliths, mountain resorts, and sacred heritage sites. Navigate multi-node panoramas, interact with historical markers, and experience high-definition 360° tourism.
          </p>

          {/* Action Button Row */}
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Link
              href="/tour/olumo-rock"
              className="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-heading font-bold text-sm uppercase tracking-wider bg-gradient-to-r from-gold-400 via-gold-500 to-gold-600 text-obsidian shadow-glow-gold hover:scale-105 active:scale-95 transition-all duration-200"
            >
              <Play className="w-4 h-4 fill-obsidian" />
              <span>Launch Olumo Rock 360°</span>
            </Link>

            <Link
              href="/explore"
              className="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl font-heading font-semibold text-sm tracking-wide text-white bg-white/5 border border-white/10 hover:bg-white/10 hover:border-gold-500/40 backdrop-blur-md transition-all duration-200"
            >
              <Compass className="w-4 h-4 text-gold-400" />
              <span>Browse All Destinations</span>
            </Link>
          </div>

          {/* Academic & Platform Metrics Counter */}
          <div className="mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-8 border-t border-white/10">
            <div className="glass-panel p-5 rounded-2xl text-center">
              <p className="font-heading font-black text-3xl sm:text-4xl text-white">
                {totalAttractions || 5}+
              </p>
              <p className="text-xs font-semibold text-gold-400 uppercase tracking-wider mt-1">
                Destinations
              </p>
            </div>
            <div className="glass-panel p-5 rounded-2xl text-center">
              <p className="font-heading font-black text-3xl sm:text-4xl text-white">
                {totalScenes || 12}+
              </p>
              <p className="text-xs font-semibold text-emerald-400 uppercase tracking-wider mt-1">
                360° Scenes
              </p>
            </div>
            <div className="glass-panel p-5 rounded-2xl text-center">
              <p className="font-heading font-black text-3xl sm:text-4xl text-white">&lt; 2.5s</p>
              <p className="text-xs font-semibold text-stone-400 uppercase tracking-wider mt-1">
                Asset Load Time
              </p>
            </div>
            <div className="glass-panel p-5 rounded-2xl text-center">
              <p className="font-heading font-black text-3xl sm:text-4xl text-white">&gt; 45 FPS</p>
              <p className="text-xs font-semibold text-stone-400 uppercase tracking-wider mt-1">
                WebGL Framerate
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* ===== FEATURED DESTINATION HERO CARD ===== */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="relative rounded-3xl overflow-hidden border border-gold-500/30 shadow-2xl glass-panel-elevated">
          <div className="grid grid-cols-1 lg:grid-cols-12 items-center">
            {/* Left Content */}
            <div className="p-8 sm:p-12 lg:col-span-7 space-y-6">
              <div className="flex flex-wrap items-center gap-2">
                <span className="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gold-500/20 text-gold-300 border border-gold-500/30">
                  Featured 360° Tour
                </span>
                <span className="px-3 py-1 rounded-full text-xs font-medium text-stone-300 bg-white/5 border border-white/10">
                  {featured.state?.name || "Ogun"}, Nigeria
                </span>
                <span className="px-3 py-1 rounded-full text-xs font-medium text-emerald-300 bg-forest-900/60 border border-forest-500/30">
                  5 Seamless Scenes
                </span>
              </div>

              <h2 className="font-heading font-black text-3xl sm:text-4xl lg:text-5xl text-white tracking-tight">
                {featured.name}
              </h2>

              <p className="text-stone-300 text-base leading-relaxed">
                {featured.short_description}
              </p>

              {/* Scene Checklist */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-stone-300 pt-2">
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-400 flex-shrink-0" />
                  <span>Main Entrance & Cultural Plaza</span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-400 flex-shrink-0" />
                  <span>Ancient Grinding Floor Depressions</span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-400 flex-shrink-0" />
                  <span>Wartime Egba Cave Shelter</span>
                </div>
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-4 h-4 text-emerald-400 flex-shrink-0" />
                  <span>137m Summit Panoramic Vista</span>
                </div>
              </div>

              <div className="pt-4 flex flex-wrap gap-4">
                <Link
                  href={`/tour/${featured.slug}`}
                  className="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-gold-400 hover:bg-gold-300 text-obsidian shadow-glow-gold transition-all"
                >
                  <Play className="w-4 h-4 fill-obsidian" />
                  Start Full Virtual Tour
                </Link>
                <Link
                  href={`/attractions/${featured.slug}`}
                  className="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl font-heading font-semibold text-xs tracking-wider text-stone-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 transition-all"
                >
                  View Historical Dossier
                </Link>
              </div>
            </div>

            {/* Right Media Graphic with Real Destination Photography */}
            <div className="relative min-h-[320px] sm:min-h-[400px] lg:h-full lg:col-span-5 p-6 flex flex-col justify-end overflow-hidden group">
              <img
                src={getAssetUrl(featured.hero_image || featured.thumbnail)}
                alt={featured.name}
                className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/60 to-black/30" />
              <div className="relative z-10 glass-panel p-5 rounded-2xl border border-white/15 backdrop-blur-md space-y-2 shadow-xl">
                <div className="flex items-center justify-between text-xs text-stone-300">
                  <span>Rendering Engine</span>
                  <span className="text-gold-400 font-mono font-bold">Pannellum 2.5.6 WebGL</span>
                </div>
                <div className="flex items-center justify-between text-xs text-stone-300">
                  <span>Equirectangular Resolution</span>
                  <span className="text-white font-mono">2048 × 1024 (2:1 Ratio)</span>
                </div>
                <div className="flex items-center justify-between text-xs text-stone-300">
                  <span>Navigation Nodes</span>
                  <span className="text-emerald-400 font-mono font-bold">Interactive Hotspots</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ===== POPULAR TOURS CAROUSEL / GRID ===== */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div className="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-10">
          <div>
            <div className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-gold-400 mb-2">
              <Camera className="w-4 h-4" />
              <span>Curated Immersive Experiences</span>
            </div>
            <h2 className="font-heading font-black text-3xl sm:text-4xl text-white tracking-tight">
              Popular 360° Virtual Tours
            </h2>
          </div>
          <Link
            href="/explore"
            className="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-stone-300 hover:text-gold-400 transition-colors"
          >
            <span>Explore All Attractions</span>
            <ArrowRight className="w-4 h-4" />
          </Link>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {attractions.map((attraction: any) => (
            <div
              key={attraction.id}
              className="group relative rounded-2xl overflow-hidden glass-panel border border-white/10 hover:border-gold-500/40 hover:shadow-2xl transition-all duration-300 flex flex-col"
            >
              {/* Card Header Media Aspect */}
              <div className="relative h-56 bg-obsidian p-5 flex flex-col justify-between overflow-hidden">
                <img
                  src={getAssetUrl(attraction.thumbnail)}
                  alt={attraction.name}
                  className="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-obsidian via-obsidian/50 to-black/30" />

                <div className="relative z-10 flex items-center justify-between">
                  <span className="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-obsidian/80 backdrop-blur-md text-gold-400 border border-gold-500/30">
                    {attraction.category?.name || "Attraction"}
                  </span>
                  <div className="flex items-center gap-1.5">
                    {attraction.view_count > 0 && (
                      <span className="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-obsidian/80 backdrop-blur-md text-stone-200 border border-white/10 flex items-center gap-1">
                        <Eye className="w-3 h-3 text-gold-400" />
                        {attraction.view_count.toLocaleString()}
                      </span>
                    )}
                    {attraction.tour_available && (
                      <span className="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-950/80 backdrop-blur-md text-emerald-300 border border-emerald-500/40 flex items-center gap-1">
                        <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
                        360° Tour
                      </span>
                    )}
                  </div>
                </div>

                <div className="relative z-10">
                  <div className="flex items-center gap-1.5 text-xs text-stone-300 mb-1">
                    <MapPin className="w-3.5 h-3.5 text-gold-400" />
                    <span>{attraction.state?.name || "Nigeria"}</span>
                  </div>
                  <h3 className="font-heading font-black text-xl text-white group-hover:text-gold-300 transition-colors drop-shadow-md">
                    {attraction.name}
                  </h3>
                </div>
              </div>

              {/* Card Body */}
              <div className="p-5 flex-1 flex flex-col justify-between space-y-4">
                <p className="text-xs text-stone-300 line-clamp-2 leading-relaxed">
                  {attraction.short_description}
                </p>

                <div className="pt-2 border-t border-white/5 flex items-center justify-between">
                  <span className="text-[11px] font-mono text-stone-400">
                    {attraction.scenes?.length || 1} Scene Node{attraction.scenes?.length === 1 ? "" : "s"}
                  </span>

                  <div className="flex items-center gap-2">
                    <Link
                      href={`/attractions/${attraction.slug}`}
                      className="text-xs text-stone-300 hover:text-white px-2.5 py-1.5 rounded-lg hover:bg-white/5"
                    >
                      Details
                    </Link>
                    <Link
                      href={`/tour/${attraction.slug}`}
                      className="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider bg-forest-600 hover:bg-gold-500 hover:text-obsidian text-white border border-forest-400/30 transition-all duration-200"
                    >
                      <span>Enter 360°</span>
                      <ArrowRight className="w-3.5 h-3.5" />
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* ===== CATEGORIES SECTION ===== */}
      <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-white/5">
        <div className="text-center max-w-2xl mx-auto mb-12">
          <span className="text-xs font-bold uppercase tracking-widest text-gold-400">
            Geographic & Cultural Taxonomy
          </span>
          <h2 className="font-heading font-black text-3xl sm:text-4xl text-white tracking-tight mt-1">
            Explore Destinations by Category
          </h2>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
          {categories.map((cat: any) => (
            <Link
              key={cat.id}
              href={`/explore?category=${cat.slug}`}
              className="glass-panel p-5 rounded-2xl border border-white/10 hover:border-gold-500/40 hover:scale-105 transition-all duration-300 text-center flex flex-col items-center justify-center space-y-2 group"
            >
              <div className="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-2xl group-hover:bg-gold-500/20 transition-colors">
                {cat.icon || "🏛"}
              </div>
              <h4 className="font-heading font-bold text-sm text-stone-200 group-hover:text-gold-300">
                {cat.name}
              </h4>
              <p className="text-[11px] text-stone-400">
                {cat._count?.attractions || 0} Destination{cat._count?.attractions === 1 ? "" : "s"}
              </p>
            </Link>
          ))}
        </div>
      </section>
    </div>
  );
}
