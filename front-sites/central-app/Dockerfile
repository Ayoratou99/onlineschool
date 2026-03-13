# --- Build stage ---
FROM node:20-alpine AS builder

WORKDIR /app

# Install dependencies
COPY package.json package-lock.json* ./
RUN npm ci

# Build argument for API URL (injected at build time when calling Laravel API)
ARG VITE_API_URL=
ENV VITE_API_URL=${VITE_API_URL}

COPY . .
RUN npm run build

# --- Production stage: serve static files with nginx ---
FROM nginx:alpine

# Remove default config and copy SPA config
RUN rm -f /etc/nginx/conf.d/default.conf
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Copy built assets from builder
COPY --from=builder /app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
