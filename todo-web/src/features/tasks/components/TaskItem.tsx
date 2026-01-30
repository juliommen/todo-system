import { Check, Trash2 } from "lucide-react";
import type { Task } from "@/features/tasks";
import { deleteTask, updateTaskStatus } from "@/features/tasks";
import { useAppDispatch } from "@/shared/hooks/useAppDispatch";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogTitle,
  AlertDialogTrigger,
  AlertDialogFooter,
  AlertDialogHeader,
} from "@/shared/components/ui/AlertDialog";
import { Button } from "@/shared/components/core/Button";
import { useAppSelector } from "@/shared/hooks/useAppSelector";
import { useState } from "react";
import Spinner from "@/shared/components/ui/Spinner";

export function TaskItem({ task }: { task: Task }) {
  const { id, status, title, description } = task;
  const dispatch = useAppDispatch();
  const { error, loading } = useAppSelector((s) => s.tasks);
  const [updating, setUpdating] = useState(false);
  const [deleting, setDeleting] = useState(false);

  async function toggle() {
    setUpdating(true);
    const next = status === "pending" ? "completed" : "pending";
    try {
      await dispatch(updateTaskStatus({ id, status: next })).unwrap();
    } catch {
    } finally {
      setUpdating(false);
    }
  }

  async function remove() {
    setDeleting(true);
    try {
      await dispatch(deleteTask(id));
    } catch {
    } finally {
      setDeleting(false);
    }
  }

  return (
    <div className="bg-gray-700 flex items-center justify-between gap-3 p-4 rounded-xl">
      <div className="flex items-center gap-3">
        {updating ? (
          <Spinner size="md" className="mx-1.5" />
        ) : (
          <Button
            disabled={!!error || loading}
            onClick={toggle}
            className={`disabled:cursor-not-allowed p-1.5 rounded-full focus:outline-none hover:bg-green-300 ${status === "completed" ? "bg-green-500" : "bg-white"}`}
          >
            <Check />
          </Button>
        )}

        <div>
          <div
            className={`text-lg font-semibold ${status === "completed" && "line-through"}`}
          >
            {title}
          </div>

          <div
            className={`text-sm text-gray-300 ${status === "completed" && "line-through"}`}
          >
            {description}
          </div>
        </div>
      </div>

      <AlertDialog>
        <AlertDialogTrigger asChild>
          {deleting ? (
            <Spinner size="md" className="mr-5" />
          ) : (
            <Button
              disabled={!!error || loading}
              className="p-4 disabled:cursor-not-allowed rounded-full disabled:hover:text-red-600  text-red-600 hover:text-red-700 focus:outline-none"
            >
              <Trash2 />
            </Button>
          )}
        </AlertDialogTrigger>
        <AlertDialogContent className="bg-gray-900">
          <AlertDialogHeader>
            <AlertDialogTitle>
              Você tem certeza que deseja excluir?
            </AlertDialogTitle>
            <AlertDialogDescription className="text-white">
              Essa ação não pode ser desfeita
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel className="bg-gray-500 border-none hover:bg-gray-400 hover:text-white">
              Cancel
            </AlertDialogCancel>
            <AlertDialogAction
              className="bg-red-600 border-none hover:bg-red-500"
              onClick={remove}
            >
              Continue
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
