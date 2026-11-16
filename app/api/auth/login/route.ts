import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { prisma } from "@/lib/prisma";
import { createSessionToken, SESSION_COOKIE, SESSION_MAX_AGE } from "@/lib/auth";

export const dynamic = "force-dynamic";

export async function POST(request: Request) {
  let email = "";
  let password = "";
  try {
    const body = await request.json();
    email = String(body.email ?? "").trim().toLowerCase();
    password = String(body.password ?? "");
  } catch {
    return NextResponse.json({ error: "Invalid request" }, { status: 400 });
  }

  if (!email || !password) {
    return NextResponse.json({ error: "Email and password are required" }, { status: 400 });
  }

  try {
    const user = await prisma.user.findUnique({ where: { email } });
    // Laravel writes $2y$ hashes; bcryptjs reads the identical $2a$ format.
    const hash = user?.password?.replace(/^\$2y\$/, "$2a$");
    const ok = hash ? await bcrypt.compare(password, hash) : false;

    if (!user || !ok) {
      return NextResponse.json({ error: "Invalid email or password" }, { status: 401 });
    }
    if (user.role !== "admin") {
      return NextResponse.json({ error: "This account does not have admin access" }, { status: 403 });
    }

    const token = await createSessionToken({
      uid: user.id.toString(),
      email: user.email,
      name: user.name,
      role: user.role,
    });

    const res = NextResponse.json({ ok: true, name: user.name });
    res.cookies.set(SESSION_COOKIE, token, {
      httpOnly: true,
      secure: process.env.NODE_ENV === "production",
      sameSite: "lax",
      path: "/",
      maxAge: SESSION_MAX_AGE,
    });
    return res;
  } catch (err) {
    console.error("Login failed:", err);
    return NextResponse.json({ error: "Login is unavailable right now" }, { status: 500 });
  }
}
