## System Requirements
Laravel Version : 13 <br/>
PHP : 8.5 <br/>
Javascript: Vue </br>
CSS: Tailwind 4</br>
Database : MySQL <br/>
## Instructions
1. Clone the project in your local
2. create database "any name"
3. Copy the .env.example to .env
4. Update the database connection in the .env 

DB_CONNECTION=mysql <br />
DB_HOST="<database server name>" <br />
DB_PORT="<database port>" <br />
DB_DATABASE="<database name>" <br />
DB_USERNAME="<database username>" <br />
DB_PASSWORD="<database password>" <br />

5. Open the cmd or command line <br />
6. Go to the projects folder <br />
7. Run <br/ >
npm install <br />
composer install <br />

8. Run migration <br />
php artisan migrate 

9. Seed User Data run command below <br />
php artisan db:seed --class=UserSeeder

10. Seed Project Data run command below <br />
php artisan db:seed --class=ProjectSeeder

11. Run key generation <br /> 
php artisan key:generate

12. Clear Cache <br /> 
config:cache

13. Link css in the storage/public in the assets
php artisan storage:link

14. Run the laravel project <br />
php artisan serve

15. From a separate terminal. Go to the project directory and run <br />
npm run dev

16. Go to the http://127.0.0.1:8000, you should be redirected in the login page

17. Input username and password <br />
username: admin@admin.com <br />
password: password <br />

18. You will be redirected to the dashboard.

19. Click the Project in the left menu.

20. Now you can test the CRUD of projects

# Features
1. Create-Read-Update-Delete of Projects
2. Filtering and Sorting of Projects
3. Pagination




