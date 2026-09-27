import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

/**
 * Serialize objects containing BigInt, Decimal, and Date values for Next.js Client Components
 */
export function serializeData<T>(data: T): T {
  return JSON.parse(
    JSON.stringify(data, (key, value) => {
      if (typeof value === "bigint") {
        return value.toString();
      }
      return value;
    })
  );
}

/**
 * Normalizes asset paths so they resolve accurately from /public
 */
export function getAssetUrl(path?: string | null, fallback = "/images/placeholder.webp"): string {
  if (!path) return fallback;
  if (path.startsWith("http")) return path;
  const clean = path.replace(/^\/?(storage\/)?/, "");
  return `/${clean}`;
}

