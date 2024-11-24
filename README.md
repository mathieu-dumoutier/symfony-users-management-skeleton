# Symfony Users Management skeleton

A skeleton repository for start new project with users management features inside!

![Roles and group matrix](/docs/roles-matrix.png)

You can create own with [GitHub template](https://github.com/new?template_name=symfony-users-management-skeleton&template_owner=mathieu-dumoutier)

![CI](https://github.com/mathieu-dumoutier/symfony-users-management-skeleton/workflows/CI/badge.svg)

Created with Symfony 7.2, current version : 7.2

## First start

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Create a `.env.local` file with the following customized content:
```dotenv
APP_SECRET=your_secret
```
4. Run `make vendor`
5. Run `make up`
6. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
7. Create first user admin

## Usage

1. Run `make up`
2. Open `https://localhost` in your favorite web browser
3. Run `make down` to stop.

## Composer et bin/console commands

Run composer with `make composer` and pass the parameter `"c="` to run a given command, example: `make composer c='req symfony/orm-pack'`

Run bin/console with `make sf` and pass the parameter `"c="` to run a given command, example: `make sf c=about`

## Useful commands

Extract missing translations with :
* `make sf c="translation:extract --force --format=json fr"` 
* `make sf c="translation:extract --force --format=json en"`

## Features

* All features of [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker)
* EasyAdmin 4 with bootstrap theme
* Tailwind CSS with Webpack Encore and Hot Module Replacement (HMR) enabled
* Entities for users management : User, Group, Role
* Form login
* Registration and verification by email (enabled by default, but disabled with `REGISTRATION_ENABLED=0` env var)
* Password reset
* Easy impersonate for user with the role `ROLE_ALLOWED_TO_SWITCH`
* Roles and groups matrix
* Flash messages displayed in [tailwind notifications](https://tailwindui.com/components/application-ui/overlays/notifications)
* Internationalization share between Symfony and Vue (with vue-i18n) in `assets/locales` directory
* CRUD interfaces for env variable configuration (first ones are `APP_NAME`, `REGISTRATION_ENABLED`, `MAILER_DSN`, `SENDER_EMAIL` and `SENDER_NAME`)

**Enjoy!**

## Tests

In order to run test, execute :
```
docker compose exec -T php bin/console -e test doctrine:database:create
docker compose exec -T php bin/console -e test doctrine:migrations:migrate --no-interaction
```

## Going to production

Generate css and js files for production with `make encore c=build`

## License

Symfony Users Management skeleton is available under the MIT License.
