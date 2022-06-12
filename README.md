## Installation

```
docker run --rm \
-v "$(pwd)":/opt \
-w /opt \
laravelsail/php81-composer:latest \
bash -c "php ./artisan sail:install --with=mysql,redis,meilisearch,mailhog,selenium "
```
```
./vendor/bin/sail up
```
## Todo list

- Write tests
- Integrate with google API

