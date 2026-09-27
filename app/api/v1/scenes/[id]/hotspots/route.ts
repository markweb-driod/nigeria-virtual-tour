import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";

export async function POST(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const body = await request.json();
    const { type, yaw, pitch, label, description, target_scene_id } = body;

    const hotspot = await prisma.hotspot.create({
      data: {
        scene_id: BigInt(id),
        type: type || "scene",
        yaw: parseFloat(yaw),
        pitch: parseFloat(pitch),
        label: label || "Hotspot",
        description: description || null,
        target_scene_id: target_scene_id ? BigInt(target_scene_id) : null,
      },
      include: {
        target_scene: {
          select: { id: true, name: true, slug: true },
        },
      },
    });

    return NextResponse.json({
      success: true,
      hotspot: serializeData(hotspot),
    });
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to create hotspot", details: String(error) },
      { status: 500 }
    );
  }
}

export async function DELETE(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const hotspotId = searchParams.get("hotspot_id");

    if (!hotspotId) {
      return NextResponse.json({ error: "Missing hotspot_id" }, { status: 400 });
    }

    await prisma.hotspot.delete({
      where: { id: BigInt(hotspotId) },
    });

    return NextResponse.json({ success: true });
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to delete hotspot", details: String(error) },
      { status: 500 }
    );
  }
}
