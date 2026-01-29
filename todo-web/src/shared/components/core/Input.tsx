import {
  InputHTMLAttributes,
  KeyboardEventHandler,
  TextareaHTMLAttributes,
} from "react";

type Props = InputHTMLAttributes<HTMLInputElement> &
  TextareaHTMLAttributes<HTMLTextAreaElement> & {
    multiline?: boolean | number;
  };

export function Input(props: Props) {
  const { multiline, className, ...rest } = props as any;

  const baseClass =
    "bg-gray-500 rounded-md px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent";

  const mergedClassName = className ? `${baseClass} ${className}` : baseClass;

  if (multiline) {
    const handleKeyDown = (event: React.KeyboardEvent<HTMLTextAreaElement>) => {
      if (event.key === "Enter") {
        event.preventDefault();
      }
    };

    return (
      <textarea
        className={mergedClassName}
        rows={Number(multiline) || 3}
        onKeyDown={handleKeyDown}
        {...(rest as TextareaHTMLAttributes<HTMLTextAreaElement>)}
      />
    );
  }

  return (
    <input
      className={mergedClassName}
      {...(rest as InputHTMLAttributes<HTMLInputElement>)}
    />
  );
}
