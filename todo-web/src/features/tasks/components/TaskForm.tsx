import type { FormEvent } from "react";
import { useState } from "react";
import { useAppDispatch } from "@/shared/hooks/useAppDispatch";
import { Button } from "@/shared/components/core/Button";
import { createTask } from "@/features/tasks";
import { Input } from "@/shared/components/core/Input";
import { ListPlusIcon } from "lucide-react";
import { useAppSelector } from "@/shared/hooks/useAppSelector";
import Spinner from "@/shared/components/ui/Spinner";

export function TaskForm() {
  const { error, loading } = useAppSelector((s) => s.tasks);
  const dispatch = useAppDispatch();
  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [creating, setCreating] = useState(false);

  async function handleSubmit(e: FormEvent) {
    e.preventDefault();

    setCreating(true);

    if (!title.trim() || !description.trim()) {
      return;
    }

    try {
      await dispatch(
        createTask({ title: title.trim(), description: description.trim() }),
      ).unwrap();

      setTitle("");
      setDescription("");
    } catch {
    } finally {
      setCreating(false);
    }
  }

  return (
    <div className="bg-gray-700 rounded-xl p-6 my-6">
      <form onSubmit={handleSubmit} className="space-y-3">
        <Input
          id="title"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          placeholder="Título"
          maxLength={50}
          required
        />

        <Input
          id="description"
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          placeholder="Descrição"
          maxLength={130}
          required
          multiline={2}
          className="resize-none"
        />

        <div className="flex justify-end">
          <Button type="submit" disabled={!!error || loading}>
            {creating ? (
              <Spinner size="sm" className="text-white" />
            ) : (
              <ListPlusIcon />
            )}
            Adicionar tarefa
          </Button>
        </div>
      </form>
    </div>
  );
}
