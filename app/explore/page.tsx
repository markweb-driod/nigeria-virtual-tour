import { prisma } from "@/lib/prisma";
import { serializeData } from "@/lib/utils";
import ExploreClient from "@/components/ExploreClient";

export const revalidate = 60;

interface ExploreProps {
  searchParams: Promise<{
    search?: string;
    category?: string;
    state?: string;
    tour?: string;
    featured?: string;
  }>;
}

export default async function ExplorePage({ searchParams }: ExploreProps) {
  const params = await searchParams;

  let attractions = [];
  let categories = [];
  let states = [];

  try {
    const rawAttractions = await prisma.attraction.findMany({
      where: { status: "published" },
      include: {
        category: true,
        state: true,
        scenes: { select: { id: true, name: true, slug: true } },
      },
      orderBy: { view_count: "desc" },
    });
    attractions = serializeData(rawAttractions);

    const rawCategories = await prisma.category.findMany({
      orderBy: { name: "asc" },
    });
    categories = serializeData(rawCategories);

    const rawStates = await prisma.state.findMany({
      orderBy: { name: "asc" },
    });
    states = serializeData(rawStates);
  } catch (e) {
    console.error("Explore data load failed:", e);
  }

  return (
    <div className="min-h-screen bg-obsidian pt-32 sm:pt-36 pb-20 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto">
        {/* Header */}
        <div className="mb-10 text-center sm:text-left">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-xs font-bold uppercase tracking-wider text-gold-400 mb-3">
            <span>Destination Catalog</span>
          </div>
          <h1 className="font-heading font-black text-3xl sm:text-5xl text-white tracking-tight">
            Explore Nigerian Attractions
          </h1>
          <p className="text-stone-300 text-sm sm:text-base mt-2 max-w-2xl">
            Browse through Nigeria's premier cultural, historical, and geological landmarks. Filter by state, category, or access full 360° virtual tours.
          </p>
        </div>

        {/* Interactive Filter & Attraction Grid */}
        <ExploreClient
          initialAttractions={attractions}
          categories={categories}
          states={states}
          initialFilters={params}
        />
      </div>
    </div>
  );
}
