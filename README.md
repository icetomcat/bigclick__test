## Installation and running

```
git clone git@github.com:icetomcat/bigclick__test.git
cd bigclick__test
UID=$(id -u) GID=$(id -g) docker-compose build
docker-compose exec php php artisan migrate:refresh --seed
docker-compose up -d
```
## Open
http://client.localhost

