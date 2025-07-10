#LOKALNIE
1. Przejść do backend i uruchomić composer install / php artisan serve
2. Przejść do frontend i uruchomić npm install / npm run dev
3. Ja używałem Apache wiec odpalić XAMPP i na nim Mysql(port 3306) i Apache
4. Wchodzić przez localhost:5173

#DOCKER
1. Przejsć do Mobilem
2. Wykonać docker compose build
2. Wykonać docker compose up
3. Przejść do kontenera z backendem (mobilem-laravel)
4. Wykonać docker exec -it mobilem-laravel php artisan migrate:fresh
5. Wykonać docker exec -it mobilem-laravel php artisan storage:link
6. Powinno działać pod localhost:5173


Przy zmianie z lokal na docker odkomentować dobry kod a zakomentować zły dotyczący bazy w mobilem/backend/.env