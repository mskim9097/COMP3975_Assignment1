## Setup (Backend)

Run the following commands ONE BY ONE:

cd backend  
composer install  
npm install  
cp .env.example .env  
php artisan key:generate  
touch database/cms-db.sqlite  
php artisan migrate --seed  
npm run build  
php artisan serve  

---

## Frontend Setup (React)

Open a new terminal and run:

cd frontend  
npm install  
npm run dev  

---

## Application URLs

- Laravel Backend / Admin: http://127.0.0.1:8000  
- React Frontend: http://localhost:5173  

Make sure the backend server is running BEFORE starting the frontend.

---

## Seed Account Info

Email: a@a.a  
Password: P@$$w0rd  

---

## REST API Test

Base URL:  
http://127.0.0.1:8000/api  

---

### 1. Users

READ  
GET http://127.0.0.1:8000/api/users  
GET http://127.0.0.1:8000/api/users/1  

CREATE  
POST http://127.0.0.1:8000/api/users  

Body (JSON):
{
  "email": "test1@test.com",
  "password": "1234"
}

UPDATE  
PUT http://127.0.0.1:8000/api/users/2  

Body (JSON):
{
  "email": "test1_changed@test.com",
  "password": "9999"
}

DELETE  
DELETE http://127.0.0.1:8000/api/users/2  

---

### 2. Articles

CREATE  
POST http://127.0.0.1:8000/api/articles  

Body (JSON):
{
  "title": "Test Article 1",
  "content": "This is the first test article."
}

READ  
GET http://127.0.0.1:8000/api/articles  
GET http://127.0.0.1:8000/api/articles/1  

UPDATE  
PUT http://127.0.0.1:8000/api/articles/1  

Body (JSON):
{
  "title": "Test Article 1 (Updated)",
  "content": "Updated content here."
}

DELETE  
DELETE http://127.0.0.1:8000/api/articles/1  

---

## CORS Test (GET only)

1. Open in browser:  
   http://127.0.0.1:8000/api/users  
   → Should return data (GET works)

2. Open test.html in browser and click POST  
   → Should return 405 or be blocked

---

## Notes

- Frontend fetches data from backend API  
- Only GET requests are allowed from browser (CORS restriction)  
- POST / PUT / DELETE work in Postman  
- Laravel Breeze requires backend assets to be installed and built using npm  