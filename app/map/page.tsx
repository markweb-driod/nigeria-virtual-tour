import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";
import MapClient from "@/components/MapClient";

export const revalidate = 60;

export default async function MapPage() {
  let attractions = [];

  try {
    const rawAttractions = await prisma.attraction.findMany({
      where: {
        status: "published",
        latitude: { not: null },
        longitude: { not: null },
      },
      include: { state: true, category: true },
    });
    attractions = serializeData(rawAttractions);
  } catch (err) {
    console.error("Map query error:", err);
  }

  return (
    <div className="min-h-screen bg-obsidian pt-20 flex flex-col">
      <div className="flex-1 flex flex-col">
        <MapClient attractions={attractions} />
      </div>
    </div>
  );
}
