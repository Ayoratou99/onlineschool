interface BadgeProps {
  children: React.ReactNode;
  variant?: 'primary' | 'secondary' | 'success' | 'warning' | 'mute';
  className?: string;
}

const variants = {
  primary: 'bg-primary/15 text-primary',
  secondary: 'bg-secondary/15 text-ink',
  success: 'bg-green-100 text-green-800',
  warning: 'bg-amber-100 text-amber-800',
  mute: 'bg-mute/20 text-mute',
};

export default function Badge({ children, variant = 'primary', className = '' }: BadgeProps) {
  return (
    <span
      className={`inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${variants[variant]} ${className}`}
    >
      {children}
    </span>
  );
}
