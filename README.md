## Запуск
Запускаем контейнеры<br>
`docker-compose up -d`<br>
Запускаем терминал <br>
`docker exec -it webserver sh`<br>
Проводим миграции, заполняем базу случайными значениями и запускаем тесты <br>
`#cd /var/www/laravel`<br/>
`#php artisan migrate`<br/>
`#php artisan db:seed`<br/>
`#php artisan test`
## Доступы к серверам
MySQL доступен на 13306 порту, логин root, пароль WngvzAmb.<br>
Веб-сервер доступен на 180 порту.<br>
Эндпойнты для Postman в файле abc.postman_collection.json<br>
Перед запуском /api/user/settings нужно получить токен в /api/user/auth
## Основные измененные файлы и каталоги (для просмотра логики кода)
<ul>
<li>app/Models</li>
<li>app/Http/Controllers/UserController.php</li>
<li>app/Http/Middleware/CheckToken.php</li>
<li>routes/api.php</li>
<li>tests/Feature</li>
</ul>
