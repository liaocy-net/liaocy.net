# Charing


## Commands

- Start Development Server
```bash
php artisan serve
```

- Generate APP Key
```bash
php artisan key:generate
```

- Generate Controller
```bash
php artisan make:controller <ControllerName> -r
```

- Add Column to User Model
```bash
php artisan make:migration add_role_to_users --table="users"
```

- Database Migration
```bash
php artisan migrate
```

- Database Migration Rollback
```bash
php artisan migrate:rollback --step=1
```

- Create Admin in CLI
```bash
php artisan tinker
```

- Create Model
```bash
php artisan make:model ForeignShipping -m
```

```php
$user = new App\Models\User();
$user->password = Hash::make('123456');
$user->email = 'test@test.com';
$user->name = 'Test User';
$user->role = 'admin';
$user->first_name = '太郎';
$user->last_name = '高木';
$user->save();
```

- Monitoring Port
```bash
lsof -i :8000
```

## Docker

### Build and Replace

```bash
$ docker build -f DockerfileWeb -t dev-charing:latest .
$ docker ps -a -q --filter="name=dev-charing" | xargs docker stop | xargs docker rm
$ docker run -p 5000:8000 -d --name dev-charing --restart=always dev-charing:latest
$ docker rmi $(docker images -f "dangling=true" -q)
$ docker logs -f dev-charing


$ docker exec -it 2201ebd62585 /bin/bash
```

## Code Snippets

- Route Resource
```php
Route::resource('users', UserController::class);
```
equals to
https://github.com/ixiumu/laravel_meetup/blob/master/docs/11.Code%20Beauty%EF%BC%88Resource%20Controllers%EF%BC%89.md

## References

- Auth
  - [Authentication](https://laravel.com/docs/10.x/authentication)
  - [Laravel 8.x 認証](https://readouble.com/laravel/8.x/ja/authentication.html)
  - [middlewareを追加して管理者権限ユーザーのみアクセスを許可する](https://ohta412.jp/laravel-middleware/)
  - [Create Custom Laravel Blade Directive & Middleware Easily](https://mahekarim.medium.com/create-custom-laravel-blade-directive-middleware-easily-75155bf00cc9)
  - [How to Disable Users from Login in Laravel](https://dev.to/techtoolindia/how-to-disable-users-from-login-in-laravel-bm9)

- Database
  - [Eloquent: Relationships](https://laravel.com/docs/10.x/eloquent-relationships#one-to-many)

- Route
  - [リダイレクトの書き方メモ](https://qiita.com/manbolila/items/767e1dae399de16813fb)

- Models
  - [Add new field to user profile in Laravel 8](https://dev.to/arifiqbal/add-new-field-to-user-profile-in-laravel-8-49ck)

- Paging
  - [イントロダクション](https://readouble.com/laravel/8.x/ja/pagination.html)
  - [Database: Pagination](https://laravel.com/docs/10.x/pagination)

- Form
  - [【laravel】Validatorによるバリデーション](https://qiita.com/gone0021/items/c613ef7e006b6f5d47ce)

- Others
  - [Laravel:419|PAGE EXPIREDエラーの解決方法](https://qiita.com/taka_no_okapi/items/fb4bbe59c18eeaf5a043)

- Files
  - [【Laravel】CSVファイルのデータインポート＆エクスポートする方法](https://qiita.com/kyo-san/items/bc8d0278bee99ae1f2ec)

- SSL
  - [LaravelアプリをSSL化する際の注意点(assetヘルパー)](https://maasaablog.com/development/backend/php/laravel/538/)

- Laravel
  - [Laravel】Ajax通信をするときはCSRFトークンをヘッダーに追加しよう！](https://akizora.tech/laravel-ajax-csrf-4263)

# Laravel 8.x

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
