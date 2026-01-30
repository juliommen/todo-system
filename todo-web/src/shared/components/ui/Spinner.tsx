import type { HTMLAttributes } from "react";

type Props = HTMLAttributes<HTMLSpanElement> & {
  size?: "sm" | "md" | "lg";
};

export function Spinner({ size = "md", className = "", ...rest }: Props) {
  const sizes: Record<string, string> = {
    sm: "w-4 h-4 border-2",
    md: "w-5 h-5 border-2",
    lg: "w-6 h-6 border-4",
  };

  return (
    <span
      className={`inline-block align-middle ${sizes[size]} rounded-full border-current border-solid border-t-transparent animate-spin ${className}`}
      {...rest}
    />
  );
}

export default Spinner;
