Submitted By: Maryam Saleem
--------------------------------------------  Modified Files  --------------------------------------------

1. Http/Controllers/userController.php
2. Http/Middleware/EmailVerifyMiddleware.php
3. Http/Middleware/ResetPassEmailMiddleware.php
4. Http/Requests/loginRequest.php
5. Http/Requests/storeUserRequest.php
6. Mail/ResetPasswordMail.php
7. Mail/VerifyMail.php
8. Models/User.php
9. Models/UserToken.php
10. database/migrations/2014_10_12_000000_create_users_table.php
11. database/migrations/2022_12_08_094739_create_token_table.php
12. userSeeder.php
13. resources/views/resetPasswordView.blade.php
14. resources/views/verificationEmail.blade.php
15. routes/api.php

--------------------------------------------  Database Name = Task6  --------------------------------------------
Database Name = Task6
Table Name = 
1. users
2. token

--------------------------------------------  Routes  --------------------------------------------

--------------------- Login: 	 http://127.0.0.1:8000/api/login 		 (Method = POST)
--------------------- Register: 		 http://127.0.0.1:8000/api/newUser 	(Method = POST)
--------------------- Forget Password:   	http://127.0.0.1:8000/api/forget 	 (Method = POST) 
--------------------- Reset Password: 	   http://127.0.0.1:8000/api/reset/{userId}  	(Method = GET)
