import type { Task, TaskStatus } from "@/features/tasks";
import { sleep } from "@/lib/utils";
import { http } from "@/shared/api/http";

const endpoint = "/tasks";

export async function fetchTasksAPI(): Promise<Task[]> {
  await sleep();
  const res = await http.get(endpoint);
  return res.data;
}

export async function createTaskAPI(payload: {
  title: string;
  description: string;
}): Promise<Task> {
  await sleep();
  const res = await http.post(endpoint, payload);
  return res.data;
}

export async function updateTaskStatusAPI(
  id: string,
  status: TaskStatus,
): Promise<Task> {
  await sleep();
  const res = await http.patch(`${endpoint}/${id}`, { status });
  return res.data;
}

export async function deleteTaskAPI(id: string): Promise<void> {
  await sleep();
  return await http.delete(`${endpoint}/${id}`);
}
