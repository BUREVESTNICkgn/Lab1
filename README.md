# Lab 2 – запуск на SQLite

Эти шаги закрывают ошибку `could not find driver` и поднимают приложение на SQLite.

## 1. Включите драйвер SQLite в PHP

В `php.ini` должны быть раскомментированы строки:

```
extension=pdo_sqlite
extension=sqlite3
```

После правки перезапустите PHP (или перезапустите терминал в Windows).

## 2. Подготовьте окружение

```bash
cp .env.example .env
php artisan key:generate
```

Файл `.env` уже настроен на SQLite (`DB_CONNECTION=sqlite`, `DB_DATABASE=./database/database.sqlite`).

## 3. Создайте файл базы и примените миграции

```bash
mkdir -p database
touch database/database.sqlite
php artisan migrate
```

Если запускаете `php artisan migrate` и видите предупреждение про создание файла БД — отвечайте `yes`.

## 4. Установите зависимости и запустите

```bash
composer install
npm install
npm run dev   # сборка front
php artisan serve
```

Очередь (если нужна): `php artisan queue:listen --tries=1`.

## 5. Где смотреть логи 500

- Основной файл: `storage/logs/laravel.log`.
- Смотреть последние записи в Unix: `tail -n 200 storage/logs/laravel.log` или потоково `tail -f storage/logs/laravel.log`.
- На Windows аналогично через PowerShell: `Get-Content storage/logs/laravel.log -Wait`.
- Если файла нет, Laravel создаст его при первом запросе; убедитесь, что папка `storage/logs` доступна для записи.
