import { fetchTasks } from "@/features/tasks";
import { TaskForm } from "@/features/tasks/components/TaskForm";
import { TaskList } from "@/features/tasks/components/TaskList";
import { ErrorAlert } from "@/shared/components/ui/ErrorAlert";
import { useAppDispatch } from "@/shared/hooks/useAppDispatch";
import { useAppSelector } from "@/shared/hooks/useAppSelector";
import { useEffect, useState } from "react";

export function TasksManager() {
  const dispatch = useAppDispatch();
  const { error } = useAppSelector((s) => s.tasks);
  const [lastError, setLastError] = useState<string | null>(null);
  const [retrying, setRetrying] = useState(false);

  useEffect(() => {
    if (error) {
      setLastError(error);
    }
  }, [error]);

  async function handleRetry() {
    if (retrying) return;
    setRetrying(true);
    try {
      await dispatch(fetchTasks()).unwrap();
      setLastError(null);
    } catch {
    } finally {
      setRetrying(false);
    }
  }

  return (
    <>
      {lastError && (
        <ErrorAlert
          message={lastError}
          onRetry={handleRetry}
          retrying={retrying}
        />
      )}

      <main className="min-h-screen flex items-start justify-center py-8 px-4">
        <section className="w-full max-w-xl">
          <header className="mb-6">
            <h1 className="text-2xl font-bold mb-4">Gerenciador de Tarefas</h1>
          </header>

          <TaskForm />

          <TaskList />
        </section>
      </main>
    </>
  );
}
