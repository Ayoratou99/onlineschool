import { TextareaHTMLAttributes, forwardRef } from 'react';

interface TextareaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
  label?: string;
  error?: string;
}

const Textarea = forwardRef<HTMLTextAreaElement, TextareaProps>(
  ({ label, error, className = '', ...props }, ref) => (
    <div className="w-full">
      {label && (
        <label className="block text-sm font-medium text-ink mb-1">{label}</label>
      )}
      <textarea
        ref={ref}
        className={`w-full px-3 py-2 border rounded-lg bg-off border-border text-ink placeholder-mute focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-y min-h-[100px] ${error ? 'border-red-500' : ''} ${className}`}
        {...props}
      />
      {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
    </div>
  )
);
Textarea.displayName = 'Textarea';
export default Textarea;
