import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { session_uuid, avg_fps, load_time_ms, transition_time_ms, completed } = body;

    if (!session_uuid) {
      return NextResponse.json({ error: "Missing session_uuid" }, { status: 400 });
    }

    await prisma.tourSession.updateMany({
      where: { session_uuid },
      data: {
        avg_fps: avg_fps ? parseFloat(avg_fps) : undefined,
        load_time_ms: load_time_ms ? parseFloat(load_time_ms) : undefined,
        transition_time_ms: transition_time_ms ? parseFloat(transition_time_ms) : undefined,
        completed: completed === true,
        ended_at: new Date(),
      },
    });

    return NextResponse.json({ success: true });
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to update session metrics", details: String(error) },
      { status: 500 }
    );
  }
}
