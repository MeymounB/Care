web: heroku-php-apache2 public/
email: php bin/console messenger:consume async
release: php bin/console cache:clear && php bin/console cache:warmup && php bin/console doctrine:migrations:migrate --no-interaction