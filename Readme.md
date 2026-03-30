### Setup ###
Run the following commands ONE BY ONE (do not run them together):

cd backend
composer install
cp .env.example .env
php artisan key:generate
touch database/cms-db.sqlite
php artisan migrate --seed
php artisan serve

---

### Seed Account info ###
Email: a@a.a  
Password: P@$$w0rd  

---

### REST API TEST ###

Base URL:
http://127.0.0.1:8000/api  

---

#### 1. For User Table

1-1. READ  
GET http://127.0.0.1:8000/api/users  
GET http://127.0.0.1:8000/api/users/1  

---

1-2. CREATE  
POST http://127.0.0.1:8000/api/users  

Body → raw → JSON  
{
  "email": "test1@test.com",
  "password": "1234"
}

GET http://127.0.0.1:8000/api/users  

---

1-3. UPDATE  
PUT http://127.0.0.1:8000/api/users/2  

Body → raw → JSON  
{
  "email": "test1_changed@test.com",
  "password": "9999"
}

GET http://127.0.0.1:8000/api/users/2  

---

1-4. DELETE  
DELETE http://127.0.0.1:8000/api/users/2  

---

#### 2. For Articles Table

2-1. CREATE  
POST http://127.0.0.1:8000/api/articles  

Body → raw → JSON  
{
  "title": "Test Article 1",
  "content": "This is the first test article."
}

---

2-2. READ  
GET http://127.0.0.1:8000/api/articles  
GET http://127.0.0.1:8000/api/articles/1  

---

2-3. UPDATE  
PUT http://127.0.0.1:8000/api/articles/1  

Body → raw → JSON  
{
  "title": "Test Article 1 (Updated)",
  "content": "Updated content here."
}

GET http://127.0.0.1:8000/api/articles/1  

---

2-4. DELETE  
DELETE http://127.0.0.1:8000/api/articles/1  

---

### CORS TEST (restricted to GET)

1. Open a browser and go to:  
   http://127.0.0.1:8000/api/users  

   → Should return data (GET works)

---

2. To test CORS restriction:

   Open test.html in a browser  
   (e.g., double-click the file or use VS Code Live Server)

   Click the POST button  

   → Should return 405 or be blocked (POST is restricted)

---

### Note:
- GET requests are allowed from browser  
- POST / PUT / DELETE are restricted via CORS  
- All methods still work in Postman