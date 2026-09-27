import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";

export async function GET(
  request: Request,
  { params }: { params: Promise<{ slug: string }> }
) {
  try {
    const { slug } = await params;

    const attraction = await prisma.attraction.findUnique({
      where: { slug },
      include: {
        scenes: {
          where: { status: "published" },
          orderBy: { sort_order: "asc" },
          include: {
            hotspots: {
              include: {
                target_scene: {
                  select: { id: true, slug: true, name: true },
                },
              },
            },
          },
        },
      },
    });

    if (!attraction) {
      return NextResponse.json({ error: "Attraction not found" }, { status: 404 });
    }

    const defaultScene =
      attraction.scenes.find((s) => s.is_default) || attraction.scenes[0];

    const scenesObj: Record<string, any> = {};

    attraction.scenes.forEach((scene) => {
      const panoramaUrl = scene.panorama_path?.startsWith("http")
        ? scene.panorama_path
        : `/${scene.panorama_path?.replace(/^\/?(storage\/)?/, "")}`;

      scenesObj[scene.slug] = {
        title: scene.name,
        panorama: panoramaUrl,
        yaw: scene.initial_yaw || 0,
        pitch: scene.initial_pitch || 0,
        hfov: scene.initial_hfov || 100,
        hotspots: scene.hotspots.map((hs) => ({
          id: hs.id.toString(),
          type: hs.type,
          yaw: hs.yaw,
          pitch: hs.pitch,
          text: hs.label,
          description: hs.description,
          sceneId: hs.target_scene?.slug,
        })),
      };
    });

    const response = {
      tour: {
        name: attraction.name,
        defaultScene: defaultScene?.slug || "",
      },
      scenes: scenesObj,
    };

    return NextResponse.json(response);
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to generate tour configuration", details: String(error) },
      { status: 500 }
    );
  }
}
