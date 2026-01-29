import axios from "axios";

export const http = axios.create({
  baseURL: (import.meta as any).env.VITE_API_URL,
});
