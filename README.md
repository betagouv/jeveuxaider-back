# Réserve Civique

## Scalingo Setup

* Create application

```
scalingo create reserve-civique-<environment>
```

* Add PostgreSQL Addon

```
scalingo addons-add postgresql <plan>
```

* Configure basics of the application

```
scalingo env-set \
  BUILDPACK_URL=https://github.com/Scalingo/multi-buildpack.git \
  PHP_BUILDPACK_NO_NODE=true \
  APP_ENV=production \
  LOG_CHANNEL=errorlog \
  DB_CONNECTION=pgsql \
  NPM_CONFIG_PRODUCTION=false \
  APP_KEY=$(openssl rand -hex 16) \
  MIX_API_BASE_URL=<url> \
  MIX_ALGOLIA_PLACES_APP_ID=<algolia id> \
  MIX_ALGOLIA_PLACES_API_KEY=<algolia key> \
  ASSET_URL=https://<asset_url>
  APP_URL=https://<app_url>
```

> `asset_url` and `app_url` can be the same according to the setup

* Configure Passport

```
php artisan passport:install
```

From the output, take the second credentials (with password authentication enabled).

Use the ID and secret in the following command:

```
scalingo env-set \
  "OAUTH_PRIVATE_KEY=$(cat storage/oauth-private.key)" \
  "OAUTH_PUBLIC_KEY=$(cat storage/oauth-public.key)" \
  MIX_OAUTH_CLIENT_ID=<id> \
  MIX_OAUTH_CLIENT_SECRET=<secret>
```

Remove these files as they should only be used in the deployed environment not in dev

```
rm storage/oauth-private.key storage/oauth-public.key
```

### Configure S3 Credentials

```
scalingo env-set \
  S3_AK=<ak> \
  S3_SK=<sk> \
  S3_ENDPOINT=<endpoint> \
  S3_REGION=<region> \
  S3_BUCKET=<bucket>
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
  MAIL_FROM_ADDRESS=crise@reserve-civique.gouv.fr \
  "MAIL_FROM_NAME=Réserve Civique"
```
