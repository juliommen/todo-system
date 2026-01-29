import { useEffect } from "react";
import { fetchTasks } from "@/features/tasks";
import { useAppSelector } from "@/shared/hooks/useAppSelector";
import { useAppDispatch } from "@/shared/hooks/useAppDispatch";
import { TaskItem } from "@/features/tasks/components/TaskItem";
import { TasksSummary } from "./TasksSummary";

export function TaskList() {
  const dispatch = useAppDispatch();
  const { items } = useAppSelector((s) => s.tasks);

  useEffect(() => {
    dispatch(fetchTasks());
  }, [dispatch]);

  return (
    <>
      <h1 className="mb-4 mt-6 text-xl">Lista de tarefas</h1>

      <TasksSummary />

      <div className="mt-4">
        {items.length === 0 ? (
          <div className="p-4  text-gray-200 bg-gray-700 rounded-xl">
            Não há tarefas criadas ainda
          </div>
        ) : (
          <div className="gap-4 flex flex-col">
            {items.map((t) => (
              <TaskItem key={t.id} task={t} />
            ))}
          </div>
        )}
      </div>
    </>
  );
}
