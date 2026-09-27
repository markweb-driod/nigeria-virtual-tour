import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";
import { notFound } from "next/navigation";
import Link from "next/link";
import AttractionDetailClient from "@/components/AttractionDetailClient";

export const revalidate = 60;

interface Props {
  params: Promise<{ slug: string }>;
}

export default async function AttractionDetailPage({ params }: Props) {
  const { slug } = await params;

  let attraction = null;
  let relatedAttractions = [];

  try {
    const rawAttraction = await prisma.attraction.findUnique({
      where: { slug },
      include: {
        category: true,
        state: true,
        scenes: {
          where: { status: "published" },
          orderBy: { sort_order: "asc" },
        },
        media_items: {
          orderBy: { sort_order: "asc" },
        },
      },
    });

    if (!rawAttraction) {
      notFound();
    }

    // Increment view count
    await prisma.attraction.update({
      where: { id: rawAttraction.id },
      data: { view_count: { increment: 1 } },
    });

    attraction = serializeData(rawAttraction);

    // Fetch related
    const rawRelated = await prisma.attraction.findMany({
      where: {
        category_id: rawAttraction.category_id,
        id: { not: rawAttraction.id },
        status: "published",
      },
      include: { state: true, category: true },
      take: 3,
    });
    relatedAttractions = serializeData(rawRelated);
  } catch (e) {
    console.error("Attraction fetch failed:", e);
    notFound();
  }

  return (
    <div className="min-h-screen bg-obsidian text-stone-100">
      <AttractionDetailClient
        attraction={attraction}
        relatedAttractions={relatedAttractions}
      />
    </div>
  );
}
