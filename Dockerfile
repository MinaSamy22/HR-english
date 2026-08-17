FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY vite.config.js ./
COPY public ./public
RUN npm run build

FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=assets /app/public/build /var/www/html/public/build

ENV SKIP_COMPOSER=1 \
    WEBROOT=/var/www/html/public \
    PHP_ERRORS_STDERR=1 \
    RUN_SCRIPTS=1 \
    REAL_IP_HEADER=1 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    COMPOSER_ALLOW_SUPERUSER=1

CMD ["/start.sh"]

EXPOSE 10000
