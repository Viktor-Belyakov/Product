# AksonProduct

1. Стягиваем зависимости - composer i
2. Собираем сборку - docker compose build
3. Запускаем контейнеры - docker compose up
4. Заходим в контейнер php - docker exec -it akson-php-1 bash
5. Запускаем слушателя - php bin/console messenger:consume
6. Импортирем файл - php bin/console app:import-products
