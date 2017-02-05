CREATE TABLE companies
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    quota BIGINT(20) NOT NULL
);
CREATE UNIQUE INDEX companies_name_uindex ON companies (name);
CREATE TABLE transfers
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    date DATETIME NOT NULL,
    user_id INT(11) NOT NULL,
    resource TEXT NOT NULL,
    transferred BIGINT(20),
    CONSTRAINT transfers_users_id_fk FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE INDEX transfers_users_id_fk ON transfers (user_id);
CREATE TABLE users
(
    id INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    company_id INT(11) NOT NULL
);
CREATE INDEX users_companies_id_fk ON users (company_id);
