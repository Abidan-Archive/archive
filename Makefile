install:
	docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs && npm i
start:
	sh vendor/bin/sail up -d && npm run dev && sh vendor/bin/sail stop
