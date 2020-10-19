
## Elaniin test

Hello Elaniin evaluator, this is a small project for the requirements that you've sent me.

### Used tech

- Laravel 8 (Deployed on AWS)
- MySQL 5.7 (Google cloud platform)
- JWT Auth (Bearer token)

### Requirements

- PHP > 7.3
- Composer
- MySQL 5.7 (Please configure on .env file)
- Email configuration (Please configure on .env file)


Please clone this repository and configure the database and email on the .env file
after that, follow the next steps
```
composer install
php artisan migrate
php artisan db:seed
php artisan storage:link

```

You can find a live version on the following link:

- [live version](https://elaniin.siliconsivar.com)
