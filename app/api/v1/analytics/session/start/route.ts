import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import crypto from "crypto";

export async function POST(request: Request) {
  try {
    const body = await request.json();
    const { attraction_id } = body;

    if (!attraction_id) {
      return NextResponse.json({ error: "Missing attraction_id" }, { status: 400 });
    }

    const session_uuid = crypto.randomUUID();
    const userAgent = request.headers.get("user-agent") || "";

    const isMobile = /mobile|iphone|android|ipad/i.test(userAgent);
    const device_type = isMobile ? "mobile" : "desktop";

    let browser = "Other";
    if (/chrome|crios/i.test(userAgent)) browser = "Chrome";
    else if (/firefox|fxios/i.test(userAgent)) browser = "Firefox";
    else if (/safari/i.test(userAgent)) browser = "Safari";
    else if (/edg/i.test(userAgent)) browser = "Edge";

    const session = await prisma.tourSession.create({
      data: {
        attraction_id: BigInt(attraction_id),
        session_uuid,
        device_type,
        browser,
        os: isMobile ? "Mobile OS" : "Desktop OS",
        started_at: new Date(),
      },
    });

    return NextResponse.json({
      session_uuid: session.session_uuid,
      session_id: session.id.toString(),
    });
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to start analytics session", details: String(error) },
      { status: 500 }
    );
  }
}
