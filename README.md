<h1>Saudi Journalists Association V2.0</h1>
<p>The backend code of the <a href="https://github.com/SH1R3F/SJU-v2.0-admin-panel">admin dashboard</a> and <a href="https://github.com/SH1R3F/SJU-users-app">users' site</a> of Saudi journalists association</p>

<h2>Setup</h2>
<p>Make sure to update environment variables</p>
<code style="white-space: pre-wrap">
mv .env.example .env \n
composer install \n
php artisan key:generate \n
php artisan migrate
</code>
