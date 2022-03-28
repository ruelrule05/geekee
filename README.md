# GEEKEE
## _Back-End API Practical Test_


A simple twitter-like app API

- Create unit test
- Create API documentation using Postman or any other tools
- Create a simple twitter-like app API
   - Ability to register (required)
   - Abiliti to login/logout
   - Create/update tweet
   - Delete tweet
   - Upload file attachment(s) to a tweet
   - Follow/unfollow a user
   - List followed user's tweet
   - List suggested users to follow

## Instructions

1. Clone the repository ```https://github.com/ruelrule05/geekee```
2. Copy example .env file ```cp .env.example .env```
3. Run command ```composer install```
4. Run command ```npm install && npm run dev```
5. Run command ```php artisan key:generate```
6. Update ```.env``` file
   - Database connection settings
   ```
   DB_CONNECTION=mysql
   DB_DATABASE=<db_name | geekee>
   DB_USERNAME=<db_user | root>
   DB_PASSWORD=<db_password | [blank]>
   ```
7. Run command ```php artisan migrate:fresh --seed```
8. Run command ```php artisan storage:link```

To run the test, use the command
```php artisan test```