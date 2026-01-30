import { Button } from "@/shared/components/core/Button";
import Spinner from "./Spinner";

type ErrorAlertProps = {
  message: string;
  onRetry: () => Promise<void> | void;
  retrying?: boolean;
};

export function ErrorAlert({
  message,
  onRetry,
  retrying = false,
}: ErrorAlertProps) {
  return (
    <div
      className={`opacity-95 w-[85%] min-w-[300px] md:w-auto fixed left-1/2 -translate-x-1/2 top-2 z-50 transition-opacity duration-200`}
    >
      <div
        role="alert"
        aria-live="assertive"
        className="flex justify-center gap-4 items-center px-4 py-2 bg-red-700 rounded-md shadow-lg"
      >
        <p className="text-lg">{message}</p>

        <Button onClick={onRetry} variant="ghost" disabled={retrying}>
          {retrying && <Spinner size="sm" className="text-white" />} Atualizar
        </Button>
      </div>
    </div>
  );
}
