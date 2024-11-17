# Symfony Users Management skeleton

A skeleton repository for start new project with users management features inside!

You can create own with [GitHub template](https://github.com/new?template_name=symfony-users-management-skeleton&template_owner=mathieu-dumoutier)

![CI](https://github.com/mathieu-dumoutier/symfony-users-management-skeleton/workflows/CI/badge.svg)

Created with Symfony 7.2, current version : 7.2

## First start

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images

## Usage

1. Run `make up`
2. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
3. Run `make down` to stop.

## Composer et bin/console commands

Run composer with `make composer` and pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'

Run bin/console with `make sf` and pass the parameter "c=" to run a given command, example: make sf c=about

## Features

* All features of [dunglas/symfony-docker](https://github.com/dunglas/symfony-docker)
* Entities for users management : User, Role, Permission

**Enjoy!**

## License

Symfony Users Management skeleton is available under the MIT License.

## Credits

Symfony-docker is created by [Kévin Dunglas](https://dunglas.dev), co-maintained by [Maxime Helias](https://twitter.com/maxhelias) and sponsored by [Les-Tilleuls.coop](https://les-tilleuls.coop).
