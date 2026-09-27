import { NextResponse } from "next/server";
import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";

export async function GET(request: Request) {
  try {
    const { searchParams } = new URL(request.url);
    const category = searchParams.get("category");
    const state = searchParams.get("state");
    const tour = searchParams.get("tour");
    const featured = searchParams.get("featured");

    const where: any = { status: "published" };
    if (category) where.category = { slug: category };
    if (state) where.state = { slug: state };
    if (tour === "true") where.tour_available = true;
    if (featured === "true") where.is_featured = true;

    const attractions = await prisma.attraction.findMany({
      where,
      include: {
        category: true,
        state: true,
        scenes: { select: { id: true, name: true, slug: true } },
      },
      orderBy: { view_count: "desc" },
    });

    return NextResponse.json(serializeData(attractions));
  } catch (error) {
    return NextResponse.json(
      { error: "Failed to fetch attractions", details: String(error) },
      { status: 500 }
    );
  }
}
