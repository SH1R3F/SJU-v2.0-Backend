<h1>Saudi Journalists Association v2.0</h1>
<p>The backend code of the <a href="https://github.com/SH1R3F/SJU-v2.0-admin-panel">admin dashboard</a> and <a href="https://github.com/SH1R3F/SJU-users-app">users' site</a> of Saudi journalists association</p>

<h2>Setup</h2>
<p>Make sure to update environment variables</p>
<pre>
mv .env.example .env 
composer install 
php artisan key:generate 
php artisan migrate
</pre>

<h2>Development serve</h2>
<p>Start the development server by running</p>
<code>php artisan serve</code>

<h2>Features of SJU v2.0</h2>
<ol>
    <li>Frontend are completely seperated from the backend, everything is connected through RESTful API</li>
    <li>3 types of users with different schema. Authenticating different users</li>
    <li>Email and mobile verification implemented</li>
    <li>Authenticating through API. Laravel sanctum used with suitable middlewares custom built</li>
    <li>Simple courses registration, attendance, and certificateable for all user types (Polymorphic relationships implemented)</li>
    <li>Annual subscription and payment integrated</li>
    <li>Technical support tickets and chats</li>
    <li>Multiple admin roles with different permissions. Laratrust used</li>
    <li>Admins can be set to manage one branch that can't manage any user out of this branch</li>
    <li>All data can be sorted and filtered in admin panel</li>
    <li>Filtered and sorted data can be exported to excel sheets</li>
    <li>Payment invoices can be managed with all information needed</li>
    <li>All users can be activated / deactivated, accepted / denied, and all info can be updated and viewed to admins with permissions</li>
    <li>Simple blog implemented</li>
</ol>
