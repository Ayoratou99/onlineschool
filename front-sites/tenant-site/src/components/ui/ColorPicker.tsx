interface ColorPickerProps {
  value: string;
  onChange: (hex: string) => void;
  label: string;
}

export default function ColorPicker({ value, onChange, label }: ColorPickerProps) {
  return (
    <div className="flex flex-col gap-2">
      <label className="text-sm font-medium text-ink">{label}</label>
      <div className="flex items-center gap-2">
        <input
          type="color"
          value={value || '#0B3D6E'}
          onChange={(e) => onChange(e.target.value)}
          className="w-12 h-12 rounded border border-border cursor-pointer"
        />
        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder="#0B3D6E"
          className="flex-1 px-3 py-2 border rounded-lg bg-off border-border text-ink font-mono text-sm"
        />
      </div>
    </div>
  );
}
