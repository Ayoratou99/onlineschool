import { useState } from 'react';
import { portailService } from '@/services/portail.service';
import toast from 'react-hot-toast';
import Spinner from './Spinner';

interface ImageUploadProps {
  value?: string;
  onChange: (url: string) => void;
  onRemove?: () => void;
  aspectRatio?: string;
  accept?: string;
  maxSizeMb?: number;
  label?: string;
}

const defaultAspect = '16/9';

export default function ImageUpload({
  value,
  onChange,
  onRemove,
  aspectRatio = defaultAspect,
  accept = 'image/png,image/jpeg,image/webp',
  maxSizeMb = 5,
  label,
}: ImageUploadProps) {
  const [uploading, setUploading] = useState(false);

  const handleFile = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    if (file.size > maxSizeMb * 1024 * 1024) {
      toast.error(`Fichier trop lourd (max ${maxSizeMb} Mo)`);
      return;
    }
    setUploading(true);
    try {
      const { url } = await portailService.uploadImage(file);
      onChange(url);
      toast.success('Image envoyée');
    } catch {
      toast.error('Erreur lors de l\'envoi');
    } finally {
      setUploading(false);
      e.target.value = '';
    }
  };

  return (
    <div className="w-full">
      {label && (
        <label className="block text-sm font-medium text-ink mb-1">{label}</label>
      )}
      {value ? (
        <div className="relative rounded-lg overflow-hidden border border-border" style={{ aspectRatio }}>
          <img src={value} alt="" className="w-full h-full object-cover" />
          <div className="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition flex items-center justify-center gap-2">
            <label className="px-3 py-1.5 bg-white rounded cursor-pointer text-sm">
              Changer
              <input type="file" accept={accept} className="hidden" onChange={handleFile} disabled={uploading} />
            </label>
            {onRemove && (
              <button type="button" onClick={onRemove} className="px-3 py-1.5 bg-red-500 text-white rounded text-sm">
                Retirer
              </button>
            )}
          </div>
        </div>
      ) : (
        <label
          className="flex flex-col items-center justify-center border-2 border-dashed border-border rounded-lg cursor-pointer hover:bg-off transition text-mute"
          style={{ aspectRatio: aspectRatio || defaultAspect }}
        >
          <input type="file" accept={accept} className="hidden" onChange={handleFile} disabled={uploading} />
          {uploading ? (
            <Spinner />
          ) : (
            <>
              <span className="text-4xl mb-2">📷</span>
              <span className="text-sm">Cliquez ou glissez une image</span>
            </>
          )}
        </label>
      )}
    </div>
  );
}
