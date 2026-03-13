import { useState } from 'react';

type Variant = 'error' | 'success' | 'info';

const variantStyles: Record<Variant, string> = {
  error: 'bg-red-50 border-red-200 text-red-800',
  success: 'bg-brand-50 border-brand-200 text-brand-800',
  info: 'bg-brand-50 border-brand-200 text-brand-800',
};

interface AlertProps {
  variant?: Variant;
  children: React.ReactNode;
  onDismiss?: () => void;
  className?: string;
}

export default function Alert({
  variant = 'info',
  children,
  onDismiss,
  className = '',
}: AlertProps) {
  const [dismissed, setDismissed] = useState(false);

  const handleDismiss = () => {
    setDismissed(true);
    onDismiss?.();
  };

  if (dismissed) return null;

  return (
    <div
      role="alert"
      className={`flex items-start gap-3 rounded-xl border px-4 py-3 shadow-sm ${variantStyles[variant]} ${className}`}
    >
      <p className="flex-1 text-sm font-medium">{children}</p>
      {onDismiss && (
        <button
          type="button"
          onClick={handleDismiss}
          className="shrink-0 rounded p-1 opacity-70 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-offset-1"
          aria-label="Dismiss"
        >
          <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
            <path
              fillRule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clipRule="evenodd"
            />
          </svg>
        </button>
      )}
    </div>
  );
}
