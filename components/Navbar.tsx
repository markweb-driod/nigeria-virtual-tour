"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState, useEffect } from "react";
import { Compass, Sparkles, MapPin, Shield, Menu, X, Globe } from "lucide-react";

export default function Navbar() {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);

  // If in the full-screen tour viewer (/tour/[slug]), hide standard navbar
  const isTourViewer = pathname?.startsWith("/tour/");

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 20);
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  if (isTourViewer) return null;

  const navLinks = [
    { href: "/", label: "Home" },
    { href: "/explore", label: "Destinations" },
    { href: "/explore?tour=true", label: "360° Tours", badge: "Live" },
    { href: "/map", label: "Interactive Map" },
    { href: "/admin", label: "Admin CMS", icon: Shield },
  ];

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled
          ? "bg-obsidian/85 backdrop-blur-xl border-b border-white/10 py-3 shadow-2xl"
          : "bg-gradient-to-b from-black/80 via-black/40 to-transparent py-5"
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        {/* Brand Logo */}
        <Link href="/" className="group flex items-center gap-3">
          <div className="relative w-10 h-10 rounded-2xl bg-gradient-to-br from-forest-500 to-forest-700 p-0.5 shadow-glow-forest group-hover:scale-105 transition-transform duration-300">
            <div className="w-full h-full bg-obsidian rounded-[14px] flex items-center justify-center border border-gold-500/30">
              <Compass className="w-5 h-5 text-gold-400 animate-spin-slow" />
            </div>
          </div>
          <div>
            <div className="flex items-center gap-1.5">
              <span className="font-heading font-black text-lg tracking-tight text-white group-hover:text-gold-300 transition-colors">
                NIGERIA
              </span>
              <span className="text-xs font-bold uppercase tracking-widest px-1.5 py-0.5 rounded bg-gold-500/20 text-gold-400 border border-gold-500/30">
                360°
              </span>
            </div>
            <p className="text-[10px] text-stone-400 font-medium tracking-wider uppercase -mt-0.5">
              Virtual Tourism Experience
            </p>
          </div>
        </Link>

        {/* Desktop Nav Items */}
        <nav className="hidden md:flex items-center gap-1 bg-white/5 border border-white/10 rounded-full px-3 py-1.5 backdrop-blur-md">
          {navLinks.map((item) => {
            const isActive = pathname === item.href;
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`relative px-4 py-2 rounded-full text-xs font-semibold tracking-wide transition-all duration-200 flex items-center gap-1.5 ${
                  isActive
                    ? "bg-gradient-to-r from-forest-600 to-forest-500 text-white shadow-md border border-forest-400/40"
                    : "text-stone-300 hover:text-white hover:bg-white/5"
                }`}
              >
                {item.icon && <item.icon className="w-3.5 h-3.5 text-gold-400" />}
                {item.label}
                {item.badge && (
                  <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse ml-0.5" />
                )}
              </Link>
            );
          })}
        </nav>

        {/* Action Button */}
        <div className="hidden lg:flex items-center gap-3">
          <Link
            href="/tour/olumo-rock"
            className="group relative inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-bold tracking-wider uppercase bg-gradient-to-r from-gold-400 to-gold-600 text-obsidian shadow-glow-gold hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-200"
          >
            <Sparkles className="w-3.5 h-3.5 fill-obsidian" />
            <span>Launch Olumo 360°</span>
          </Link>
        </div>

        {/* Mobile menu trigger */}
        <button
          onClick={() => setMobileOpen(!mobileOpen)}
          className="md:hidden p-2 rounded-xl bg-white/5 border border-white/10 text-stone-200 hover:text-white"
        >
          {mobileOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
        </button>
      </div>

      {/* Mobile Drawer */}
      {mobileOpen && (
        <div className="md:hidden px-4 pt-4 pb-6 bg-obsidian-surface border-b border-white/10 mt-3 space-y-2">
          {navLinks.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              onClick={() => setMobileOpen(false)}
              className={`block px-4 py-3 rounded-xl text-sm font-semibold ${
                pathname === item.href
                  ? "bg-forest-600 text-white"
                  : "text-stone-300 hover:bg-white/5"
              }`}
            >
              {item.label}
            </Link>
          ))}
          <div className="pt-2">
            <Link
              href="/tour/olumo-rock"
              onClick={() => setMobileOpen(false)}
              className="w-full inline-flex justify-center items-center gap-2 px-5 py-3 rounded-xl font-bold bg-gold-400 text-obsidian"
            >
              <Sparkles className="w-4 h-4" />
              Launch Olumo 360° Tour
            </Link>
          </div>
        </div>
      )}
    </header>
  );
}
