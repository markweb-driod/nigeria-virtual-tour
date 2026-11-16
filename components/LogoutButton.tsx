"use client";

import { useState } from "react";
import { LogOut } from "lucide-react";

export default function LogoutButton() {
  const [busy, setBusy] = useState(false);

  async function logout() {
    setBusy(true);
    await fetch("/api/auth/logout", { method: "POST" }).catch(() => {});
    window.location.href = "/login";
  }

  return (
    <button
      type="button"
      onClick={logout}
      disabled={busy}
      className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-heading font-bold text-xs uppercase tracking-wider border border-white/15 text-stone-300 hover:text-white hover:bg-white/5 transition-all disabled:opacity-50"
    >
      <LogOut className="w-4 h-4" />
      <span>{busy ? "Signing out…" : "Sign out"}</span>
    </button>
  );
}
