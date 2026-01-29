import { Check, Trash2 } from "lucide-react";
import { deleteTask, updateTaskStatus, Task } from "@/features/tasks";
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

export function TaskItem({ task }: { task: Task }) {
  const { id, status, title, description } = task;
  const dispatch = useAppDispatch();
  const { error } = useAppSelector((s) => s.tasks);

  function toggle() {
    const next = status === "pending" ? "completed" : "pending";
    dispatch(updateTaskStatus({ id, status: next }));
  }

  function remove() {
    dispatch(deleteTask(id));
  }

  return (
    <div className="bg-gray-700 flex items-center justify-between gap-3 p-4 rounded-xl">
      <div className="flex items-center gap-3">
        <Button
          disabled={!!error}
          onClick={toggle}
          className={`disabled:cursor-not-allowed p-1.5 rounded-full ${status === "completed" ? "bg-green-500" : "bg-white"} focus:outline-none`}
        >
          <Check />
        </Button>

        <div>
          <div
            className={`text-lg font-semibold ${status === "completed" && "line-through"}`}
          >
            {title}
          </div>

          <div
            className={`text-sm text-gray-200 ${status === "completed" && "line-through"}`}
          >
            {description}
          </div>
        </div>
      </div>

      <AlertDialog>
        <AlertDialogTrigger asChild>
          <Button
            disabled={!!error}
            className="p-4 disabled:cursor-not-allowed rounded-full disabled:hover:text-red-600  text-red-600 hover:text-red-500 focus:outline-none"
          >
            <Trash2 size={24} />
          </Button>
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
