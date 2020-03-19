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
  "OAUTH_PUBLIC_KEY=$(cat storage/oauth-public.key)" \
  MIX_OAUTH_CLIENT_ID=<id> \
  MIX_OAUTH_CLIENT_SECRET=<secret> \
  MIX_API_BASE_URL=<url> \
  MIX_ALGOLIA_PLACES_APP_ID=<algolia id> \
  MIX_ALGOLIA_PLACES_API_KEY=<algolia key> \
  S3_AK=<ak> \
  S3_SK=<sk> \
  S3_ENDPOINT=<endpoint> \
  S3_REGION=<region> \
  S3_BUCKET=<bucket>

# Remove these files as they should only be used in the deployed environment not in dev
rm storage/oauth-private.key storage/oauth-public.key
```

### Configuration Email

```
scalingo env-set \
  MAIL_DRIVER=smtp \
  MAIL_HOST=<smtp server> \
  MAIL_PORT=<smtp port> \
  MAIL_USERNAME=<smtp username> \
  MAIL_PASSWORD=<smtp password> \
  MAIL_ENCRYPTION=<null|tls> \
  MAIL_FROM_ADDRESS=crise@reserve-civique.gouv.fr
```
