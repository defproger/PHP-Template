# MVC Template

## Описание проекта

Этот шаблон представляет собой готовое решение для быстрого развертывания проектов без фреймворков.

Docker настроен для работы на **Linux**, **Mac (включая M1/M2)** и **Windows**.

Шаблон включает:

- PHP 8.3 + Apache
- MVC
- PSR-12
- PSR-4
- SOLID principles (+/-)
- MySQL


- Docker
    - **Zsh** для удобства работы в контейнере
    - Автоматическое выполнение `composer dump-autoload` при изменении файлов
    - Поддержка `php.ini` с настройками `error_reporting`
    - Автоматическая загрузка дампа базы данных при первом запуске контейнера

## Структура проекта

```
Template/
├── db/
│   └── baseData.sql         # Дамп базы данных
├── src/
│   ├── App/                 # Исходный код приложения
│   ├── public/              # Веб-директория (корневая директория для Apache)
│   ├── views/               # Шаблоны
│   └── .env                 # Файл переменных окружения
├── vendor/                  # Папка для зависимостей Composer
├── .gitignore
├── composer.json
├── docker-compose.yml
├── Dockerfile
├── php.ini                  # Настройки PHP
├── watcher.sh               # Сценарий для автоматического выполнения composer dump-autoload
└── README.md
```

## Быстрый старт

Создайте копию файла `.env.example` и назовите его `.env`:

```bash
docker-compose up --build
```

--build — пересоберет образы, если были изменения в Dockerfile.

Перейдите по адресу: http://localhost:8080

Подключение к базе данных

- **Хост**: `localhost` (`db` для Docker)
- **Порт**: `3306`
- **Пользователь**: `user`
- **Пароль**: `pass`
- **База данных**: `base`

## Полезные команды

Подключение к контейнеру с PHP/Apache

```bash
docker exec -it movieAdmin-web zsh
```

Запуск composer dump-autoload вручную

```bash
docker exec -it movieAdmin-web composer dump-autoload -o
```

Запуск SQL-запросов в контейнере с MariaDB

```bash
docker exec -it movieAdmin-db mysql -u user -ppass base
```
