import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";
import { notFound } from "next/navigation";
import HotspotEditorClient from "@/components/HotspotEditorClient";

interface Props {
  params: Promise<{ id: string }>;
}

export default async function HotspotEditorPage({ params }: Props) {
  const { id } = await params;

  let scene = null;
  let allScenesInAttraction = [];

  try {
    const rawScene = await prisma.scene.findUnique({
      where: { id: BigInt(id) },
      include: {
        attraction: {
          include: {
            scenes: {
              select: { id: true, name: true, slug: true },
            },
          },
        },
        hotspots: {
          include: {
            target_scene: {
              select: { id: true, name: true, slug: true },
            },
          },
        },
      },
    });

    if (!rawScene) {
      notFound();
    }

    scene = serializeData(rawScene);
    allScenesInAttraction = serializeData(rawScene.attraction.scenes);
  } catch (err) {
    console.error("Failed to load scene editor:", err);
    notFound();
  }

  return (
    <div className="min-h-screen bg-obsidian pt-20 pb-16 px-4 sm:px-6 lg:px-8">
      <HotspotEditorClient
        scene={scene}
        availableScenes={allScenesInAttraction}
      />
    </div>
  );
}
