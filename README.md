#LOKALNIE
1. Przejść do backend i uruchomić composer install / php artisan serve
2. Wykonac php artisan migrate
3. Wykonac php artisan storage:link
4. W razie potrzeby skonfigurować config/cors.php (ewentualnie usunac i publishnac nowy)
5. Przejść do frontend i uruchomić npm install / npm run dev
6. Ja używałem Apache wiec odpalić XAMPP i na nim Mysql(port 3306) i Apache
7. Wchodzić przez localhost:5173

#DOCKER
1. Przejsć do Mobilem
2. Wykonać docker compose build
2. Wykonać docker compose up
3. Wykonać docker exec -it mobilem-laravel php artisan migrate:fresh
4. Wykonać docker exec -it mobilem-laravel php artisan storage:link
5. Powinno działać pod localhost:5173


Przy zmianie z lokal na docker odkomentować dobry kod a zakomentować zły dotyczący bazy w mobilem/backend/.env


BACKUP do ENV

# Local
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=announcements
# DB_USERNAME=root
# DB_PASSWORD=

#Docker
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mobilem
DB_USERNAME=mobilemuser
DB_PASSWORD=mobilempass
