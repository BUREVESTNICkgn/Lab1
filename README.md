# Lab 2 – запуск на SQLite

Эти шаги закрывают ошибку `could not find driver` и поднимают приложение на SQLite.

## 1. Включите драйвер SQLite в PHP

В `php.ini` должны быть раскомментированы строки:

```
extension=pdo_sqlite
extension=sqlite3
```

После правки перезапустите PHP (или перезапустите терминал в Windows).

**Windows-подсказки:**

- В сборках XAMPP/WAMP путь обычно `C:\xampp\php\php.ini` или `C:\wamp64\bin\php\phpX.Y.Z\php.ini`.
- Если строка начинается с `;extension=pdo_sqlite` или `;extension=sqlite3`, уберите точку с запятой.
- Проверьте, что PHP действительно видит драйверы: `php -m | findstr sqlite` (PowerShell) должен вывести `pdo_sqlite` и `sqlite3`.
- Если используете отдельный бинарь PHP, убедитесь, что `ext`-папка лежит рядом и в `php.ini` настроен `extension_dir`.

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
