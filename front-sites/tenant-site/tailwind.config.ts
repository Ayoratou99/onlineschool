import type { Config } from 'tailwindcss';

export default {
  content: ['./index.html', './src/**/*.{js,ts,jsx,tsx}'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['DM Sans', 'system-ui', 'sans-serif'],
      },
      colors: {
        primary: 'var(--tp)',
        secondary: 'var(--ts)',
        ink: 'var(--ink)',
        off: 'var(--off)',
        border: 'var(--bdr)',
        mute: 'var(--mute)',
      },
    },
  },
  plugins: [],
} satisfies Config;
