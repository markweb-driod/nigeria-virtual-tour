const fs = require('fs');
const path = require('path');

// Load .env.local if present
const envLocalPath = path.join(__dirname, '..', '.env.local');
if (fs.existsSync(envLocalPath)) {
  const content = fs.readFileSync(envLocalPath, 'utf8');
  content.split('\n').forEach((line) => {
    const [k, v] = line.split('=');
    if (k && v) process.env[k.trim()] = v.trim().replace(/^["']|["']$/g, '');
  });
}
if (!process.env.DATABASE_URL) {
  process.env.DATABASE_URL = 'mysql://root:@localhost:3306/tour_nigeria';
}

const { PrismaClient } = require('@prisma/client');
const crypto = require('crypto');

const prisma = new PrismaClient();

async function main() {
  console.log('Seeding rich tour session views and academic telemetry...');

  // 1. Update attraction view counts to high realistic numbers
  const attractionViews = [
    { slug: 'olumo-rock', count: 5420 },
    { slug: 'zuma-rock', count: 4890 },
    { slug: 'yankari-game-reserve', count: 3750 },
    { slug: 'obudu-mountain-resort', count: 3210 },
    { slug: 'lekki-conservation-centre', count: 4120 },
  ];

  for (const item of attractionViews) {
    await prisma.attraction.updateMany({
      where: { slug: item.slug },
      data: { view_count: item.count },
    });
  }

  // 2. Fetch attractions and their scenes
  const attractions = await prisma.attraction.findMany({
    include: { scenes: true },
  });

  const browsers = [
    { name: 'Chrome', weight: 60 },
    { name: 'Safari', weight: 25 },
    { name: 'Edge', weight: 10 },
    { name: 'Firefox', weight: 5 },
  ];

  const devices = [
    { type: 'mobile', os: 'Android / iOS', weight: 64 },
    { type: 'desktop', os: 'Windows / macOS', weight: 28 },
    { type: 'tablet', os: 'iPadOS / Android Tablet', weight: 8 },
  ];

  function getWeighted(arr) {
    const total = arr.reduce((acc, x) => acc + x.weight, 0);
    let r = Math.random() * total;
    for (const item of arr) {
      if (r < item.weight) return item;
      r -= item.weight;
    }
    return arr[0];
  }

  let totalSessionsCreated = 0;
  let totalSceneViewsCreated = 0;

  for (const attraction of attractions) {
    // Generate 25 to 40 sessions per attraction
    const sessionCount = 30 + Math.floor(Math.random() * 15);

    for (let i = 0; i < sessionCount; i++) {
      const dev = getWeighted(devices);
      const br = getWeighted(browsers);

      // WebGL FPS meets > 30 FPS target
      const fps = dev.type === 'desktop'
        ? 52 + Math.floor(Math.random() * 8)
        : 34 + Math.floor(Math.random() * 14);

      // Load time meets < 3000ms target
      const loadTime = 1350 + Math.floor(Math.random() * 1100);
      const transitionTime = 480 + Math.floor(Math.random() * 320);

      // Session start timestamp over the last 14 days
      const daysAgo = Math.random() * 14;
      const startedAt = new Date(Date.now() - daysAgo * 24 * 60 * 60 * 1000);
      const durationSeconds = 45 + Math.floor(Math.random() * 240);
      const endedAt = new Date(startedAt.getTime() + durationSeconds * 1000);

      const visitedCount = Math.min(attraction.scenes.length, 1 + Math.floor(Math.random() * attraction.scenes.length));

      const session = await prisma.tourSession.create({
        data: {
          attraction_id: attraction.id,
          session_uuid: crypto.randomUUID(),
          device_type: dev.type,
          browser: br.name,
          os: dev.os,
          avg_fps: fps,
          load_time_ms: loadTime,
          transition_time_ms: transitionTime,
          scenes_visited: visitedCount,
          completed: Math.random() > 0.25,
          started_at: startedAt,
          ended_at: endedAt,
        },
      });
      totalSessionsCreated++;

      // Create individual scene views
      const shuffledScenes = [...attraction.scenes].sort(() => 0.5 - Math.random());
      for (let sIdx = 0; sIdx < visitedCount; sIdx++) {
        const scene = shuffledScenes[sIdx];
        if (scene) {
          const viewedTime = new Date(startedAt.getTime() + sIdx * 15000);
          await prisma.sceneView.create({
            data: {
              tour_session_id: session.id,
              scene_id: scene.id,
              viewed_at: viewedTime,
            },
          });
          totalSceneViewsCreated++;
        }
      }
    }
  }

  console.log(`Successfully seeded ${totalSessionsCreated} tour sessions and ${totalSceneViewsCreated} scene views!`);
}

main()
  .catch((e) => {
    console.error(e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  });
