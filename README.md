# Todo List Backend

## Environment
* Laravel 8.54
* PHP 7.3
* MySQL 5.7

## Include
* [JWT](https://github.com/tymondesigns/jwt-auth) for authentication

## Initial

* composer install
* cp .env.example .env (if need it)
* php artisan key:generate (if need it)
* php artisan jwt:secret (if need it)
* .env add param 'PAGE_LIMIT', type int

## Test

* php artisan test --env=testing
* todo Services 單元測試
* todo CRUD API 功能測試

## Seeder

* php artisan db:seed
* 建立admin帳號

## API & document

* [POST]   /api/accounts        新增帳戶
* [POST]   /api/login           登入取得jwt Token
* [POST]   /api/logout          登出 使Token失效

* [GET]    /api/todos           取得 該帳戶的 Todo 列表
* [GET]    /api/todos/{todo_id} 取得該 Todo 資料
* [POST]   /api/todos/          新增 Todo 
* [PUT]]   /api/todos/{todo_id} 更新 Todo
* [DELETE] /api/todos/{todo_id} 軟刪除 Todo

* Document: https://documenter.getpostman.com/view/11440549/UVe9TA4M

## Logic

* 將Controller 邏輯拆分出Service & Repo
* Service      負責寫業務邏輯
* Repo         負責資料邏輯（Orm 查詢）
* Controller   負責接收參數與驗證




