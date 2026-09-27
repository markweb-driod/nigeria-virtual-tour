import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";
import { notFound } from "next/navigation";
import TourViewer from "@/components/TourViewer";

interface Props {
  params: Promise<{ slug: string }>;
}

export default async function TourPage({ params }: Props) {
  const { slug } = await params;

  let attraction = null;

  try {
    const rawAttraction = await prisma.attraction.findUnique({
      where: { slug },
      include: {
        category: true,
        state: true,
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

    if (!rawAttraction || !rawAttraction.tour_available) {
      notFound();
    }

    attraction = serializeData(rawAttraction);
  } catch (e) {
    console.error("Tour fetch failed:", e);
    notFound();
  }

  return (
    <div className="fixed inset-0 bg-obsidian overflow-hidden z-50">
      <TourViewer attraction={attraction} />
    </div>
  );
}
