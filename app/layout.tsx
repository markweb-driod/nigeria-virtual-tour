import type { Metadata } from "next";
import "./globals.css";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";

export const metadata: Metadata = {
  title: "Virtual Tour Nigeria — Immersive 360° Panoramic Experiences",
  description:
    "Discover Nigeria's most breathtaking cultural and natural attractions in ultra-realistic 360° panoramic virtual tours. Experience Olumo Rock, Zuma Rock, Yankari, and beyond.",
  keywords: [
    "Nigeria",
    "Virtual Tour",
    "360 Panorama",
    "Olumo Rock",
    "Zuma Rock",
    "Tourism Nigeria",
    "WebGL",
  ],
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en" className="dark scroll-smooth">
      <body className="min-h-screen bg-obsidian text-stone-100 selection:bg-gold-500 selection:text-obsidian antialiased flex flex-col">
        <Navbar />
        <main className="flex-1">{children}</main>
        <Footer />
      </body>
    </html>
  );
}
