import { useAppSelector } from "@/shared/hooks/useAppSelector";
import { Badge } from "@/shared/components/ui/Badge";

export function TasksSummary() {
  const { items } = useAppSelector((s) => s.tasks);

  const concluded = items.filter((task) => task.status === "completed");

  return (
    <div className="flex items-center justify-between py-4">
      <Badge label="Criadas">{items.length}</Badge>

      <Badge label="Concluídas">
        {concluded.length} de {items.length}
      </Badge>
    </div>
  );
}
