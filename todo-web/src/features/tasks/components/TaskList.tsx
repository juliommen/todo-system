import { useEffect } from "react";
import { fetchTasks } from "@/features/tasks";
import { useAppSelector } from "@/shared/hooks/useAppSelector";
import { useAppDispatch } from "@/shared/hooks/useAppDispatch";
import { TaskItem } from "@/features/tasks/components/TaskItem";
import { TasksSummary } from "./TasksSummary";
import { Spinner } from "@/shared/components/core/Spinner";

export function TaskList() {
  const dispatch = useAppDispatch();
  const { items, loading } = useAppSelector((s) => s.tasks);

  useEffect(() => {
    dispatch(fetchTasks());
  }, [dispatch]);

  return (
    <>
      <h1 className="text-xl">Lista de tarefas</h1>

      <TasksSummary />

      <>
        {items.length === 0 ? (
          <div className="p-4  text-gray-300 bg-gray-700 rounded-xl">
            {loading ? (
              <>
                <Spinner size="md" className="mr-2" /> Carregando
              </>
            ) : (
              <p>Não há tarefas criadas ainda</p>
            )}
          </div>
        ) : (
          <div className="gap-4 flex flex-col">
            {items.map((t) => (
              <TaskItem key={t.id} task={t} />
            ))}
          </div>
        )}
      </>
    </>
  );
}
