/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: false, // Pannellum performs DOM manipulation
  images: {
    unoptimized: true,
  },
  eslint: {
    ignoreDuringBuilds: true,
  },
  typescript: {
    ignoreBuildErrors: true,
  },
};

export default nextConfig;
