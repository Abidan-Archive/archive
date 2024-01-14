start:
	sh vendor/bin/sail up -d && npm run dev && sh vendor/bin/sail stop

install:
	zsh bin/install

sail-install:
	docker run --rm -u "$(shell id -u):$(shell id -g)" -v "$(shell pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs

