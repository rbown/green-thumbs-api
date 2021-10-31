## Getting Started

- `cp .env.example .env`
- `composer install`
- `./vendor/bin/sail up`
- `./vendor/bin/sail artisan key:generate`
- `./vendor/bin/sail artisan migrate`
- If you want some dummy data run `./vendor/bin/sail artisan db:seed`
- To allow for local file storage use run `./vendor/bin/sail artisan storage:link`

The API will be running on http://localhost:8989

## Running Tests

`./vendor/bin/sail test`
