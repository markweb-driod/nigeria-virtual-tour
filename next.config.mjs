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
  experimental: {
    // Ship the database CA cert with every serverless function so Prisma can
    // verify the TLS connection (DATABASE_URL uses sslcert=aiven-ca.pem).
    outputFileTracingIncludes: {
      "/**/*": ["./prisma/aiven-ca.pem"],
    },
  },
};

export default nextConfig;
