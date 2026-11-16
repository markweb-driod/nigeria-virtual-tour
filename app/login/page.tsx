"use client";

import { Suspense, useState } from "react";
import { useSearchParams } from "next/navigation";
import { Shield, Mail, Lock, LogIn, Sparkles, MousePointerClick, Check, Loader2 } from "lucide-react";

const DEMO_ACCOUNTS = [
  {
    role: "Administrator",
    description: "Full access to the CMS, hotspot editor and analytics",
    email: "admin@tour.ng",
    password: "admin123",
  },
];

function LoginForm() {
  const params = useSearchParams();
  const next = params.get("next");
  const redirectTo = next && next.startsWith("/") && !next.startsWith("//") ? next : "/admin";

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);
  const [filled, setFilled] = useState<string | null>(null);

  function fillDemo(account: (typeof DEMO_ACCOUNTS)[number]) {
    setEmail(account.email);
    setPassword(account.password);
    setError(null);
    setFilled(account.email);
  }

  async function onSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError(null);
    try {
      const res = await fetch("/api/auth/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
      });
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        setError(data.error || "Sign in failed");
        setLoading(false);
        return;
      }
      window.location.href = redirectTo;
    } catch {
      setError("Network error. Please try again.");
      setLoading(false);
    }
  }

  const inputClass =
    "w-full pl-11 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-stone-500 text-sm focus:outline-none focus:border-gold-400/60 focus:ring-2 focus:ring-gold-400/20 transition";

  return (
    <div className="w-full max-w-md space-y-6">
      <div className="text-center">
        <div className="mx-auto mb-4 w-14 h-14 rounded-2xl bg-gradient-to-br from-forest-500 to-forest-700 p-0.5 shadow-glow-forest">
          <div className="w-full h-full bg-obsidian rounded-[14px] flex items-center justify-center border border-gold-500/30">
            <Shield className="w-6 h-6 text-gold-400" />
          </div>
        </div>
        <h1 className="font-heading font-black text-3xl text-white tracking-tight">Admin Sign In</h1>
        <p className="text-stone-400 text-sm mt-1">Manage scenes, hotspots and tour analytics</p>
      </div>

      {/* Demo credentials */}
      <div className="rounded-2xl border border-gold-500/30 bg-gold-500/[0.06] p-4">
        <div className="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest text-gold-400 mb-3">
          <Sparkles className="w-3.5 h-3.5" />
          <span>Demo login details</span>
          <span className="ml-auto flex items-center gap-1 normal-case tracking-normal font-medium text-stone-400">
            <MousePointerClick className="w-3.5 h-3.5" /> Click to fill
          </span>
        </div>
        <div className="space-y-2">
          {DEMO_ACCOUNTS.map((acc) => {
            const active = filled === acc.email;
            return (
              <button
                key={acc.email}
                type="button"
                onClick={() => fillDemo(acc)}
                className={`w-full text-left rounded-xl border px-4 py-3 transition-all ${
                  active
                    ? "border-gold-400/70 bg-gold-400/10"
                    : "border-white/10 bg-white/[0.03] hover:border-gold-400/50 hover:bg-white/[0.06]"
                }`}
              >
                <div className="flex items-center justify-between gap-3">
                  <span className="font-heading font-bold text-sm text-white">{acc.role}</span>
                  {active ? (
                    <span className="flex items-center gap-1 text-[11px] font-semibold text-emerald-400">
                      <Check className="w-3.5 h-3.5" /> Filled
                    </span>
                  ) : (
                    <span className="text-[11px] font-semibold text-gold-400">Use this account</span>
                  )}
                </div>
                <p className="text-[11px] text-stone-400 mt-0.5">{acc.description}</p>
                <div className="mt-2 grid grid-cols-[auto_1fr] gap-x-3 gap-y-0.5 text-xs font-mono">
                  <span className="text-stone-500">Email</span>
                  <span className="text-stone-200 break-all">{acc.email}</span>
                  <span className="text-stone-500">Password</span>
                  <span className="text-stone-200">{acc.password}</span>
                </div>
              </button>
            );
          })}
        </div>
      </div>

      <form
        onSubmit={onSubmit}
        className="rounded-2xl border border-white/10 bg-white/[0.03] backdrop-blur-md p-6 space-y-4"
      >
        <label className="block">
          <span className="block text-xs font-semibold text-stone-300 mb-1.5">Email</span>
          <div className="relative">
            <Mail className="w-4 h-4 text-stone-500 absolute left-4 top-1/2 -translate-y-1/2" />
            <input
              type="email"
              autoComplete="username"
              required
              value={email}
              onChange={(e) => {
                setEmail(e.target.value);
                setFilled(null);
              }}
              placeholder="you@example.com"
              className={inputClass}
            />
          </div>
        </label>

        <label className="block">
          <span className="block text-xs font-semibold text-stone-300 mb-1.5">Password</span>
          <div className="relative">
            <Lock className="w-4 h-4 text-stone-500 absolute left-4 top-1/2 -translate-y-1/2" />
            <input
              type="password"
              autoComplete="current-password"
              required
              value={password}
              onChange={(e) => {
                setPassword(e.target.value);
                setFilled(null);
              }}
              placeholder="••••••••"
              className={inputClass}
            />
          </div>
        </label>

        {error && (
          <p role="alert" className="text-sm text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg px-3 py-2">
            {error}
          </p>
        )}

        <button
          type="submit"
          disabled={loading}
          className="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-heading font-bold text-xs uppercase tracking-wider bg-gold-400 hover:bg-gold-300 text-obsidian shadow-glow-gold transition-all disabled:opacity-60"
        >
          {loading ? <Loader2 className="w-4 h-4 animate-spin" /> : <LogIn className="w-4 h-4" />}
          <span>{loading ? "Signing in…" : "Sign in"}</span>
        </button>
      </form>
    </div>
  );
}

export default function LoginPage() {
  return (
    <main className="min-h-screen bg-obsidian flex items-center justify-center px-4 pt-28 pb-16">
      <Suspense fallback={null}>
        <LoginForm />
      </Suspense>
    </main>
  );
}
