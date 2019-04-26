

## Set up

create an .env file (do not include APP_NAME and APP_DEBUG should be false) and run the following commands.

`composer install -vvv ` - install php packages

`npm install` - install npm packages

`php artisan key:generate` - generate application key

`php artisan migrate:refresh —seed` - create db schema and load dummy data

`php artisan passport:install`  - install default OAuth2 clients

`php artisan passport:keys` - install applications OAuth2 secrets (if it shows the key already exist, then skip it)


##Default users




### .env file

````
BROADCAST_DRIVER=pusher
QUEUE_CONNECTION=database

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

````

Please register an account on Pusher website and fill in up with the above value.



### Queue server

1. Install supervisor, Read through: https://laravel.com/docs/5.7/queues#supervisor-configuration

````
command=php artisan queue:work database --queue=high,default --sleep=3 --tries=3
````
note: change your artisan location

2. start supervisor