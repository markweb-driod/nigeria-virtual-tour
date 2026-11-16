import { NextResponse, type NextRequest } from "next/server";
import { SESSION_COOKIE, verifySessionToken } from "@/lib/auth";

export async function middleware(request: NextRequest) {
  const { pathname, search } = request.nextUrl;
  const session = await verifySessionToken(request.cookies.get(SESSION_COOKIE)?.value);
  const isAdmin = session?.role === "admin";

  // Signed-in admins skip the login form.
  if (pathname === "/login") {
    return isAdmin ? NextResponse.redirect(new URL("/admin", request.url)) : NextResponse.next();
  }

  if (isAdmin) return NextResponse.next();

  // Hotspot write API: reject instead of redirecting.
  if (pathname.startsWith("/api/")) {
    return NextResponse.json({ error: "Unauthorized" }, { status: 401 });
  }

  const loginUrl = new URL("/login", request.url);
  loginUrl.searchParams.set("next", pathname + search);
  return NextResponse.redirect(loginUrl);
}

export const config = {
  matcher: ["/admin/:path*", "/api/v1/scenes/:path*", "/login"],
};
