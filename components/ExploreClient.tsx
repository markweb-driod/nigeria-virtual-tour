"use client";

import { useState, useMemo } from "react";
import Link from "next/link";
import {
  Search,
  MapPin,
  Sparkles,
  ArrowRight,
  Filter,
  SlidersHorizontal,
  Compass,
  RotateCcw,
} from "lucide-react";

interface Props {
  initialAttractions: any[];
  categories: any[];
  states: any[];
  initialFilters: {
    search?: string;
    category?: string;
    state?: string;
    tour?: string;
    featured?: string;
  };
}

export default function ExploreClient({
  initialAttractions,
  categories,
  states,
  initialFilters,
}: Props) {
  const [search, setSearch] = useState(initialFilters.search || "");
  const [selectedCategory, setSelectedCategory] = useState(initialFilters.category || "all");
  const [selectedState, setSelectedState] = useState(initialFilters.state || "all");
  const [tourOnly, setTourOnly] = useState(initialFilters.tour === "true" || false);
  const [featuredOnly, setFeaturedOnly] = useState(initialFilters.featured === "true" || false);
  const [sortBy, setSortBy] = useState<"popular" | "name" | "newest">("popular");

  const filteredAttractions = useMemo(() => {
    return initialAttractions
      .filter((item) => {
        // Search filter
        if (search.trim()) {
          const q = search.toLowerCase();
          const matchName = item.name.toLowerCase().includes(q);
          const matchDesc = item.short_description?.toLowerCase().includes(q);
          const matchState = item.state?.name?.toLowerCase().includes(q);
          if (!matchName && !matchDesc && !matchState) return false;
        }

        // Category filter
        if (selectedCategory !== "all") {
          if (item.category?.slug !== selectedCategory) return false;
        }

        // State filter
        if (selectedState !== "all") {
          if (item.state?.slug !== selectedState) return false;
        }

        // 360 Tour availability
        if (tourOnly && !item.tour_available) return false;

        // Featured
        if (featuredOnly && !item.is_featured) return false;

        return true;
      })
      .sort((a, b) => {
        if (sortBy === "popular") return (b.view_count || 0) - (a.view_count || 0);
        if (sortBy === "name") return a.name.localeCompare(b.name);
        return 0;
      });
  }, [initialAttractions, search, selectedCategory, selectedState, tourOnly, featuredOnly, sortBy]);

  const resetFilters = () => {
    setSearch("");
    setSelectedCategory("all");
    setSelectedState("all");
    setTourOnly(false);
    setFeaturedOnly(false);
    setSortBy("popular");
  };

  return (
    <div className="space-y-8">
      {/* Controls Bar */}
      <div className="glass-panel p-5 rounded-3xl border border-white/10 space-y-4 shadow-xl">
        {/* Search input + toggles */}
        <div className="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
          {/* Search bar */}
          <div className="relative flex-1">
            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search destinations (e.g. Olumo Rock, Zuma, Caves, Ogun)..."
              className="w-full bg-obsidian-surface/80 border border-white/10 rounded-2xl pl-11 pr-4 py-3 text-sm text-white placeholder-stone-400 focus:outline-none focus:border-gold-500/60 focus:ring-1 focus:ring-gold-500/40"
            />
            {search && (
              <button
                onClick={() => setSearch("")}
                className="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-stone-400 hover:text-white"
              >
                Clear
              </button>
            )}
          </div>

          {/* Quick Filter Badges */}
          <div className="flex flex-wrap items-center gap-2">
            <button
              onClick={() => setTourOnly(!tourOnly)}
              className={`px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 ${
                tourOnly
                  ? "bg-emerald-900/80 border border-emerald-500 text-emerald-300 shadow-glow-forest"
                  : "bg-white/5 border border-white/10 text-stone-300 hover:bg-white/10"
              }`}
            >
              <span className={`w-2 h-2 rounded-full ${tourOnly ? "bg-emerald-400 animate-pulse" : "bg-stone-500"}`} />
              360° Tours Only
            </button>

            <button
              onClick={() => setFeaturedOnly(!featuredOnly)}
              className={`px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 ${
                featuredOnly
                  ? "bg-gold-900/60 border border-gold-500 text-gold-300 shadow-glow-gold"
                  : "bg-white/5 border border-white/10 text-stone-300 hover:bg-white/10"
              }`}
            >
              <Sparkles className="w-3.5 h-3.5" />
              Featured
            </button>

            {(search || selectedCategory !== "all" || selectedState !== "all" || tourOnly || featuredOnly) && (
              <button
                onClick={resetFilters}
                className="px-3 py-2.5 rounded-xl text-xs font-medium text-stone-400 hover:text-white flex items-center gap-1 hover:bg-white/5"
              >
                <RotateCcw className="w-3.5 h-3.5" />
                Reset
              </button>
            )}
          </div>
        </div>

        {/* Dropdown Filters & Sorting */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3 border-t border-white/5 text-xs">
          {/* Category Dropdown */}
          <div>
            <label className="block text-stone-400 font-medium mb-1.5">Category</label>
            <select
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
              className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-3 py-2.5 text-stone-200 focus:outline-none focus:border-gold-500/50"
            >
              <option value="all">All Categories</option>
              {categories.map((c) => (
                <option key={c.id} value={c.slug}>
                  {c.name}
                </option>
              ))}
            </select>
          </div>

          {/* State Dropdown */}
          <div>
            <label className="block text-stone-400 font-medium mb-1.5">Nigerian State</label>
            <select
              value={selectedState}
              onChange={(e) => setSelectedState(e.target.value)}
              className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-3 py-2.5 text-stone-200 focus:outline-none focus:border-gold-500/50"
            >
              <option value="all">All States</option>
              {states.map((s) => (
                <option key={s.id} value={s.slug}>
                  {s.name} State
                </option>
              ))}
            </select>
          </div>

          {/* Sort By */}
          <div>
            <label className="block text-stone-400 font-medium mb-1.5">Order By</label>
            <select
              value={sortBy}
              onChange={(e: any) => setSortBy(e.target.value)}
              className="w-full bg-obsidian-surface border border-white/10 rounded-xl px-3 py-2.5 text-stone-200 focus:outline-none focus:border-gold-500/50"
            >
              <option value="popular">Most Popular</option>
              <option value="name">Alphabetical (A-Z)</option>
            </select>
          </div>
        </div>
      </div>

      {/* Results Count Banner */}
      <div className="flex items-center justify-between text-xs text-stone-400 px-1">
        <span>
          Showing <strong className="text-white font-mono">{filteredAttractions.length}</strong> destination{filteredAttractions.length === 1 ? "" : "s"}
        </span>
      </div>

      {/* Grid of Destination Cards */}
      {filteredAttractions.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredAttractions.map((attraction) => (
            <div
              key={attraction.id}
              className="group relative rounded-3xl overflow-hidden glass-panel border border-white/10 hover:border-gold-500/50 hover:shadow-2xl transition-all duration-300 flex flex-col"
            >
              {/* Card Thumbnail Area */}
              <div className="relative h-60 bg-gradient-to-br from-forest-800 via-forest-950 to-obsidian p-5 flex flex-col justify-between overflow-hidden">
                <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(212,160,23,0.2),transparent_60%)]" />

                <div className="relative z-10 flex items-center justify-between">
                  <span className="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-obsidian/80 backdrop-blur-md text-gold-400 border border-gold-500/30">
                    {attraction.category?.name}
                  </span>
                  {attraction.tour_available && (
                    <span className="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-950/80 text-emerald-300 border border-emerald-500/40 flex items-center gap-1">
                      <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" />
                      360° Panorama
                    </span>
                  )}
                </div>

                <div className="relative z-10">
                  <div className="flex items-center gap-1.5 text-xs text-stone-300 mb-1">
                    <MapPin className="w-3.5 h-3.5 text-gold-400" />
                    <span>{attraction.state?.name}, Nigeria</span>
                  </div>
                  <h3 className="font-heading font-black text-2xl text-white group-hover:text-gold-300 transition-colors">
                    {attraction.name}
                  </h3>
                </div>
              </div>

              {/* Card Body */}
              <div className="p-6 flex-1 flex flex-col justify-between space-y-5">
                <p className="text-xs text-stone-300 line-clamp-3 leading-relaxed">
                  {attraction.short_description}
                </p>

                <div className="pt-3 border-t border-white/5 flex items-center justify-between">
                  <span className="text-[11px] font-mono text-stone-400">
                    {attraction.scenes?.length || 1} Scene{attraction.scenes?.length === 1 ? "" : "s"}
                  </span>

                  <div className="flex items-center gap-2">
                    <Link
                      href={`/attractions/${attraction.slug}`}
                      className="text-xs text-stone-300 hover:text-white px-3 py-2 rounded-xl hover:bg-white/5 border border-transparent hover:border-white/10 transition-colors"
                    >
                      Dossier
                    </Link>
                    {attraction.tour_available && (
                      <Link
                        href={`/tour/${attraction.slug}`}
                        className="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-gradient-to-r from-forest-600 to-forest-500 hover:from-gold-500 hover:to-gold-600 hover:text-obsidian text-white border border-forest-400/40 transition-all duration-200 shadow-md"
                      >
                        <span>360° Tour</span>
                        <ArrowRight className="w-3.5 h-3.5" />
                      </Link>
                    )}
                  </div>
                </div>
              </div>
            </div>
          ))}
        </div>
      ) : (
        /* Empty State */
        <div className="glass-panel p-16 rounded-3xl border border-white/10 text-center max-w-md mx-auto space-y-4">
          <Compass className="w-12 h-12 text-gold-400 mx-auto animate-pulse" />
          <h3 className="font-heading font-bold text-lg text-white">No destinations found</h3>
          <p className="text-xs text-stone-400">
            No attractions match your current filter selection. Try changing the category or clearing the search terms.
          </p>
          <button
            onClick={resetFilters}
            className="px-5 py-2.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-white/10 hover:bg-white/20 text-white transition-all"
          >
            Clear All Filters
          </button>
        </div>
      )}
    </div>
  );
}
