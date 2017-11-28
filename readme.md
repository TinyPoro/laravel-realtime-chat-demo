# Realtime Chat with Laravel 5.4, VueJS, and Laravel Echo Server

## Development

1. Clone or fork this repository
1. Run `composer install`
1. Run `php artisan key:generate`
1. Fill out relevant items in your `.env` file, including:

    ```
    DB_CONNECTION
    BROADCAST_DRIVER=redis
    ```
1. Install Redis
    Linux
    ```
    wget http://download.redis.io/redis-stable.tar.gz
    tar xvzf redis-stable.tar.gz
    cd redis-stable
    make
    ```
    
    Windows
    ```
    install redis package at `https://github.com/rgl/redis/downloads`
    run the `top .exe` (ignore the "download as zip" button)
    ```
1. Install Laravel Echo Server
    1. Install npm package globally `npm install -g laravel-echo-server`
    2. Run the init command in your project directory `laravel-echo-server init`. Configure follow the instruction on `https://github.com/tlaverdure/laravel-echo-server`
    3. Change the host and app_key in `assets/js/boootrap.js` to your host and app_key(in your `laravel-echo-server.json` file)
    ```
    window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'your-host',
    auth:
        {
            headers:
                {
                    'Authorization': 'Bearer ' + "your app_key"
                }
        }
    });
    ```
    4. Run The Server `laravel-echo-server start`
1. Run `npm i`
1. Build assets with `npm run dev`
1. Use `php artisan serve` or another method to view the app in the browser

## Important Notes
1. You can also click the `room{id}` at the hompape but go directly to room chat with url `http://you-url/chat/room{id}` with id is any number you want as well. In the second option, if the room has not exist, a new room will be created if you are the user with `id=1 (admin)` or you will be redirected to the homepage in the others cases.
2. You can delete your chat-text and the admin can delete any chat-text.


