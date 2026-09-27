import type { Config } from "tailwindcss";

const config: Config = {
  content: [
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "./pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        forest: {
          50: "#F0F8F5",
          100: "#D9EFE6",
          200: "#B3DFCDB",
          300: "#7EC7AC",
          400: "#4AA887",
          500: "#13543F",
          600: "#0B3D2E",
          700: "#082F23",
          800: "#052018",
          900: "#03140F",
          950: "#010A07",
        },
        gold: {
          50: "#FFFDF2",
          100: "#FEF7D6",
          200: "#FDEC9F",
          300: "#F9DA62",
          400: "#F5C842",
          500: "#D4A017",
          600: "#AA7A0B",
          700: "#805807",
          800: "#553804",
          900: "#2B1A01",
        },
        obsidian: {
          DEFAULT: "#070C0A",
          surface: "#0D1612",
          card: "#121E19",
          elevated: "#182721",
          border: "#1F342C",
        },
      },
      fontFamily: {
        heading: ["var(--font-heading)", "Manrope", "Sora", "sans-serif"],
        body: ["var(--font-body)", "Inter", "sans-serif"],
      },
      boxShadow: {
        "glow-gold": "0 0 35px -5px rgba(212, 160, 23, 0.35)",
        "glow-forest": "0 0 35px -5px rgba(11, 61, 46, 0.45)",
        "glass": "0 8px 32px 0 rgba(0, 0, 0, 0.37)",
        "inner-glow": "inset 0 1px 1px 0 rgba(255, 255, 255, 0.15)",
      },
      animation: {
        "spin-slow": "spin 20s linear infinite",
        "pulse-glow": "pulseGlow 3s ease-in-out infinite",
        "float": "float 5s ease-in-out infinite",
        "shimmer": "shimmer 2.5s infinite linear",
      },
      keyframes: {
        pulseGlow: {
          "0%, 100%": { opacity: "0.6", transform: "scale(1)" },
          "50%": { opacity: "1", transform: "scale(1.05)" },
        },
        float: {
          "0%, 100%": { transform: "translateY(0)" },
          "50%": { transform: "translateY(-8px)" },
        },
        shimmer: {
          "0%": { backgroundPosition: "-200% 0" },
          "100%": { backgroundPosition: "200% 0" },
        },
      },
      backdropBlur: {
        xs: "2px",
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms"),
    require("@tailwindcss/typography"),
  ],
};

export default config;
