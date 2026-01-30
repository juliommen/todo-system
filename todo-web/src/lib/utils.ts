import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
}

export async function sleep(ms = 1000) {
  if (import.meta.env.VITE_APP_ENV === "development") {
    await new Promise((resolve) => setTimeout(resolve, ms));
  }
}
