# Réserve Civique

## Scalingo Setup

```
scalingo create reserve-civique-<environment>
scalingo addons-add postgresql <plan>
php artisan passport:install
scalingo env-set \
  BUILDPACK_URL=https://github.com/Scalingo/multi-buildpack.git \
  PHP_BUILDPACK_NO_NODE=true \
  APP_ENV=production \
  LOG_CHANNEL=errorlog \
  DB_CONNECTION=pgsql \
  NPM_CONFIG_PRODUCTION=false \
  APP_KEY=$(openssl rand -hex 16) \
  "OAUTH_PRIVATE_KEY=$(cat storage/oauth-private.key)" \
  "OAUTH_PUBLIC_KEY=$(cat storage/oauth-public.key)"

# Remove these files as they should only be used in the deployed environment not in dev
rm storage/oauth-private.key storage/oauth-public.key
```
