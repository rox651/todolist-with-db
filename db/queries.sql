DROP DATABASE IF EXISTS todolist;
CREATE DATABASE todolist;
USE todolist;

DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS todos;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    pass VARCHAR(100) NOT NULL
);


CREATE TABLE todos (
    id INT AUTO_INCREMENT PRIMARY KEY,
      user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    completed VARCHAR(3) NOT NULL,
      FOREIGN KEY (user_id) REFERENCES  users(id)
);

INSERT INTO users (name,email,pass) VALUES("Homero","homerojose69@gmail.com","$2y$10$XmH.A71El1BhfFIMdoN70O6VCC2Zl5bv3miFggTm1Y2p.g8nKQ6jy")

-- INSERT INTO todos (name,completed) VALUES ("Comer", "no");