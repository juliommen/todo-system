type BadgeProps = {
  children: React.ReactNode;
  label?: string;
};

export function Badge({ children, label }: BadgeProps) {
  return (
    <div className={`flex items-center gap-2`}>
      <p className="text-md">{label}</p>
      <div className={`rounded-full bg-green-700 px-3`}>
        <span className="text-sm">{children}</span>
      </div>
    </div>
  );
}

export default Badge;
