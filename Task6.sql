CREATE TABLE Users
( 
	user_id integer PRIMARY KEY NOT NULL,
  	user_name varchar(255) NOT NULL,
  	user_age integer NOT NULL,
  	user_email varchar(200) NOT NULL UNIQUE,
  	user_phone varchar(200) NOT NULL,
  	user_pass varchar(200) NOT NULL
);