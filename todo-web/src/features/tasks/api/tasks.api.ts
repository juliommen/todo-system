import type { Task, TaskStatus } from "@/features/tasks";
import { http } from "@/shared/api/http";

const endpoint = "/tasks";

export async function fetchTasksAPI(): Promise<Task[]> {
  const res = await http.get(endpoint);
  return res.data;
}

export async function createTaskAPI(payload: {
  title: string;
  description: string;
}): Promise<Task> {
  const res = await http.post(endpoint, payload);
  return res.data;
}

export async function updateTaskStatusAPI(
  id: string,
  status: TaskStatus,
): Promise<Task> {
  const res = await http.patch(`${endpoint}/${id}`, { status });
  return res.data;
}

export async function deleteTaskAPI(id: string): Promise<void> {
  return await http.delete(`${endpoint}/${id}`);
}
