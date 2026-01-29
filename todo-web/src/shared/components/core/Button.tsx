import { forwardRef, ButtonHTMLAttributes } from "react";

type Props = ButtonHTMLAttributes<HTMLButtonElement> & {
  variant?: "primary" | "ghost";
};

export const Button = forwardRef<HTMLButtonElement, Props>(
  ({ variant = "primary", children, ...rest }, ref) => {
    const base =
      "px-3 py-2 rounded-md font-medium focus:outline-none flex items-center gap-2 transition duration-200";

    const disable = rest.disabled
      ? "disabled:bg-green-700 disabled:cursor-not-allowed opacity-50"
      : "";

    const cls =
      variant === "primary"
        ? `${base} ${disable} bg-green-700 text-white hover:bg-green-800 `
        : `${base} ${disable} bg-transparent border hover:border-gray-300 hover:text-gray-300`;

    return (
      <button ref={ref} className={`${cls}`} {...rest}>
        {children}
      </button>
    );
  },
);

Button.displayName = "Button";
