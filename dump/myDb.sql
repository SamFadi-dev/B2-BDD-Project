SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

/*---------------------------------------------------*/
/* ------------Créer toutes les tables --------------*/
/*---------------------------------------------------*/

CREATE TABLE IF NOT EXISTS CD (
    CD_NUMBER INT PRIMARY KEY,
    TITLE VARCHAR(255),
    PRODUCER VARCHAR(255),
    YEAR INT CHECK (YEAR >= 1800),
    COPIES INT
);

CREATE TABLE IF NOT EXISTS CLIENT (
    CLIENT_NUMBER INT PRIMARY KEY,
    FIRST_NAME VARCHAR(255),
    LAST_NAME VARCHAR(255),
    EMAIL_ADDRESS VARCHAR(255),
    PHONE_NUMBER VARCHAR(20) CHECK (LENGTH(PHONE_NUMBER) <= 20),
    CONSTRAINT CHK_CLIENT_NAME_LENGTH CHECK (LENGTH(FIRST_NAME) <= 100 AND LENGTH(LAST_NAME) <= 100)
);

CREATE TABLE IF NOT EXISTS DJ (
    ID INT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS EMPLOYEE (
    ID INT PRIMARY KEY,
    FIRSTNAME VARCHAR(255),
    LASTNAME VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS EVENTPLANNER (
    ID INT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS GENRE (
    NAME VARCHAR(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS LOCATION (
    ID INT PRIMARY KEY,
    STREET VARCHAR(255),
    CITY VARCHAR(255),
    POSTAL_CODE VARCHAR(10) CHECK (LENGTH(POSTAL_CODE) <= 10),
    COUNTRY VARCHAR(255),
    COMMENT VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS MANAGER (
    ID INT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS PLAYLIST (
    NAME VARCHAR(255) PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS EVENT (
    ID INT PRIMARY KEY,
    NAME VARCHAR(255),
    DATE DATE,
    DESCRIPTION VARCHAR(255),
    CLIENT INT,
    MANAGER INT,
    EVENT_PLANNER INT,
    DJ INT,
    THEME VARCHAR(255),
    TYPE VARCHAR(255),
    LOCATION INT,
    RENTAL_FEE DECIMAL(10, 2),
    PLAYLIST VARCHAR(255),
    FOREIGN KEY (CLIENT) REFERENCES CLIENT(CLIENT_NUMBER),
    FOREIGN KEY (MANAGER) REFERENCES MANAGER(ID),
    FOREIGN KEY (EVENT_PLANNER) REFERENCES EVENTPLANNER(ID),
    FOREIGN KEY (DJ) REFERENCES DJ(ID),
    FOREIGN KEY (THEME) REFERENCES THEME(NAME),
    FOREIGN KEY (LOCATION) REFERENCES LOCATION(ID) ON DELETE RESTRICT,
    FOREIGN KEY (PLAYLIST) REFERENCES PLAYLIST(NAME)
);

CREATE TABLE IF NOT EXISTS SONG (
    CD_NUMBER INT,
    TRACK_NUMBER INT,
    TITLE VARCHAR(255),
    ARTIST VARCHAR(255),
    DURATION TIME,
    GENRE VARCHAR(255),
    PRIMARY KEY (CD_NUMBER, TRACK_NUMBER),
    FOREIGN KEY (CD_NUMBER) REFERENCES CD(CD_NUMBER),
    FOREIGN KEY (GENRE) REFERENCES GENRE(NAME)
);

CREATE TABLE IF NOT EXISTS CONTAINS (
    PLAYLIST VARCHAR(255),
    TRACK_NUMBER INT,
    CD_NUMBER INT,
    PRIMARY KEY (PLAYLIST, TRACK_NUMBER, CD_NUMBER),
    FOREIGN KEY (PLAYLIST) REFERENCES PLAYLIST(NAME) ON DELETE CASCADE,
    FOREIGN KEY (TRACK_NUMBER, CD_NUMBER) REFERENCES SONG(TRACK_NUMBER, CD_NUMBER) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS SPECIALIZATION (
    DJ INT,
    GENRE VARCHAR(255),
    PRIMARY KEY (DJ, GENRE),
    FOREIGN KEY (DJ) REFERENCES DJ(ID),
    FOREIGN KEY (GENRE) REFERENCES GENRE(NAME)
);

CREATE TABLE IF NOT EXISTS SPECIALIZES (
    SUBGENRE VARCHAR(255),
    GENRE VARCHAR(255),
    PRIMARY KEY (SUBGENRE, GENRE),
    FOREIGN KEY (GENRE) REFERENCES GENRE(NAME)
);

CREATE TABLE IF NOT EXISTS SUPERVISION (
    SUPERVISOR_ID INT,
    EMPLOYEE_ID INT,
    PRIMARY KEY (SUPERVISOR_ID, EMPLOYEE_ID),
    FOREIGN KEY (SUPERVISOR_ID) REFERENCES MANAGER(ID),
    FOREIGN KEY (EMPLOYEE_ID) REFERENCES EMPLOYEE(ID)
);

CREATE TABLE IF NOT EXISTS SUITABLEFOR (
    THEME VARCHAR(255),
    PLAYLIST VARCHAR(255),
    PRIMARY KEY (THEME, PLAYLIST),
    FOREIGN KEY (THEME) REFERENCES THEME(NAME),
    FOREIGN KEY (PLAYLIST) REFERENCES PLAYLIST(NAME)
);

CREATE TABLE IF NOT EXISTS THEME (
    NAME VARCHAR(255) PRIMARY KEY
);

/*---------------------------------------------------*/
/*--Load toutes les tables depuis les fichiers .csv--*/
/*---------------------------------------------------*/


INSERT IGNORE INTO CD (CD_NUMBER, TITLE, PRODUCER, YEAR, COPIES) VALUES
(1, 'Ray of Light', 'William Orbit', 1998, 2),
(2, 'The Velvet Rope', 'Jimmy Jam and Terry Lewis', 1997, 3),
(3, 'Post', 'Björk', 1995, 3),
(4, 'The Queen Is Dead', 'The Smiths, Morrissey and Johnny Marr', 1986, 3),
(5, 'Stripped', 'Scott Storch and Linda Perry', 2002, 3),
(6, '...Baby On More Time', 'Max Martin and Rami Yacoub', 1999, 2);

INSERT IGNORE INTO CLIENT (CLIENT_NUMBER, FIRST_NAME, LAST_NAME, EMAIL_ADDRESS, PHONE_NUMBER)
VALUES
(1, 'Madaline', 'Perkins', 'm.perkins@randatmail.com', '510-2847-58'),
(3, 'Sydney', 'Myers', 's.myers@randatmail.com', '494-8945-49'),
(5, 'Vincent', 'Bailey', 'v.bailey@randatmail.com', '370-4019-69'),
(7, 'Thomas', 'Nelson', 't.nelson@randatmail.com', '926-5744-25'),
(9, 'Lydia', 'Hamilton', 'l.hamilton@randatmail.com', '502-0443-48');

INSERT IGNORE INTO CONTAINS (PLAYLIST, TRACK_NUMBER, CD_NUMBER)
VALUES
    ('Vampire Mood', 1, 4),
    ('Vampire Mood', 7, 4),
    ('Vampire Mood', 3, 4),
    ('Spooky', 1, 3),
    ('Spooky', 5, 3),
    ('Spooky', 8, 3),
    ('Fantasy 1', 2, 1),
    ('Fantasy 1', 4, 1),
    ('Fantasy 1', 8, 1),
    ('Fantasy 2', 2, 2),
    ('Fantasy 2', 5, 2),
    ('Fantasy 2', 8, 2),
    ('Summer Vibes', 2, 5),
    ('Summer Vibes', 4, 5),
    ('Summer Vibes', 10, 5),
    ('Beach', 1, 6),
    ('Beach', 3, 6),
    ('Beach', 6, 6),
    ('Generic Playlist', 2, 1),
    ('Generic Playlist', 5, 2),
    ('Generic Playlist', 8, 3),
    ('Generic Playlist', 1, 4),
    ('Generic Playlist', 2, 5),
    ('Basic Playlist', 1, 6),
    ('Basic Playlist', 3, 4),
    ('Basic Playlist', 8, 2);

INSERT IGNORE INTO DJ (ID)
VALUES (7), (8), (9), (10);

INSERT IGNORE INTO EMPLOYEE (ID, FIRSTNAME, LASTNAME)
VALUES (1, 'Karen', 'McScream'),
       (2, 'Ken', 'Bland'),
       (3, 'David', 'Tutera'),
       (4, 'Mindy', 'Weiss'),
       (5, 'Preston', 'Baily'),
       (6, 'Colin', 'Cowie'),
       (7, 'Paris', 'Hilton'),
       (8, 'Brian', 'Firkus'),
       (9, 'Sonny John', 'Moore'),
       (10, 'Tijs Michiel', 'Verwest');

INSERT IGNORE INTO EVENT (ID, NAME, DATE, DESCRIPTION, CLIENT, MANAGER, EVENT_PLANNER, DJ, THEME, TYPE, LOCATION, RENTAL_FEE, PLAYLIST)
VALUES
(1, "Madeline's Divorce Party Part I", '2023-04-01', "Madeline wants to celebrate her divorce and party hard. On the first day, she wants to feel a bit emotional and wants a darker theme.", 1, 1, 3, 7, 'Vampires', 'Divorce', 3, 1500, 'Vampire Mood'),
(2, "Vincent's Birthday Party", '2023-04-01', NULL, 5, 1, 4, 8, 'Lord of the Rings', 'Birthday', 1, 950, 'Fantasy 2'),
(3, "Madeline's Divorce Party Part II", '2023-04-03', "Madeline wants to celebrate her divorce and party hard. On the second day, she wants to get back on her feet with a tropical theme. We need lots of balloons to brighten up the place.", 1, 2, 5, 9, 'Tropical', 'Divorce', 3, 1000, 'Summer Vibes'),
(4, "Cynthia's Goodbye Party", '2023-04-03', 'Lydia wants to organize a goodbye party for her good friend Cynthia.', 9, 2, 6, 10, 'Tropical', 'Goodbye Party', 2, 9000, NULL),
(5, "Mother's Birthday Party", '2023-04-05', "Sydney wants to organize a surprise party for her daughter who's really into vampires.", 3, 1, 3, 8, 'Vampires', 'Birthday', NULL, NULL, 'Spooky');

INSERT IGNORE INTO EVENTPLANNER (ID) VALUES (3), (4), (5), (6);

INSERT IGNORE INTO GENRE (NAME)
VALUES 
('Pop'),
('Rock'),
('Dance'),
('Hip Hop'),
('Electronic'),
('Jazz'),
('Blues'),
('Country'),
('Classical'),
('Folk'),
('Metal'),
('Electronic Pop'),
('Art Pop'),
('Indie Rock'),
('Trip Hop'),
('Dance Pop'),
('R&B'),
('Heavy Metal'),
('House'),
('Bluegrass'),
('Indie folk'),
('Rap'),
('Pop rock');

INSERT IGNORE INTO LOCATION (ID, STREET, CITY, POSTAL_CODE, COUNTRY, COMMENT) 
VALUES 
(1, 'Rue des Lilas', 'Brussels', '1000', 'Belgium', 'Near the city center'),
(2, 'Avenue Louise', 'Brussels', '1050', 'Belgium', 'High-end shopping district'),
(3, 'Rue des Bouchers', 'Brussels', '1000', 'Belgium', 'Famous restaurant street'),
(4, 'Quai des Charbonnages', 'Antwerp', '2000', 'Belgium', 'On the waterfront'),
(5, 'Grand-Place', 'Tournai', '7500', 'Belgium', 'Historic town center'),
(6, 'Chaussée de Louvain', 'Leuven', '3000', 'Belgium', 'University district');

INSERT IGNORE INTO MANAGER (ID)
VALUES (1), (2);

INSERT IGNORE INTO PLAYLIST (NAME) VALUES 
('Vampire Mood'),
('Spooky'),
('Fantasy 1'),
('Fantasy 2'),
('Summer Vibes'),
('Beach'),
('Generic Playlist'),
('Basic Playlist');

INSERT IGNORE INTO SONG (CD_NUMBER, TRACK_NUMBER, TITLE, ARTIST, DURATION, GENRE)
VALUES 
(1, 2, 'Swim', 'Madonna', '4:00', 'Pop'),
(1, 4, 'Candy Perfume Girl', 'Madonna', '4:36', 'Electronic Pop'),
(1, 8, 'Shanti/Ashtangi', 'Madonna', '4:29', 'Electronic Pop'),
(1, 11, 'To Have and Not to Hold', 'Madonna', '5:23', 'Electronic Pop'),
(2, 2, 'You', 'Janet Jackson', '4:42', 'Trip Hop'),
(2, 5, 'Together Again', 'Janet Jackson', '5:01', 'Dance Pop'),
(2, 8, 'Special', 'Janet Jackson', '4:57', 'R&B'),
(3, 1, 'Army of Me', 'Björk', '3:54', 'Art Pop'),
(3, 5, 'It s Oh So Quiet', 'Björk', '3:38', 'Art Pop'),
(3, 8, 'I Miss You', 'Björk', '4:04', 'Art Pop'),
(4, 1, 'The Queen is Dead', 'The Smiths', '6:24', 'Rock'),
(4, 3, 'I Know It s Over', 'The Smiths', '5:49', 'Indie Rock'),
(4, 7, 'The Boy with the Thorn in His Side', 'The Smiths', '3:16', 'Indie Rock'),
(4, 9, 'There Is a Light That Never Goes Out', 'The Smiths', '4:03', 'Indie Rock'),
(4, 10, 'Some Girls Are Bigger Than Others', 'The Smiths', '3:14', 'Rock'),
(5, 2, 'Beautiful', 'Christina Aguilera', '4:00', 'Pop'),
(5, 4, 'Cruz', 'Christina Aguilera', '3:49', 'Pop'),
(5, 10, 'Keep on Singin  My Song', 'Christina Aguilera', '6:31', 'Pop'),
(6, 1, '...Baby One More Time', 'Britney Spears', '3:30', 'Pop'),
(6, 3, 'Sometimes', 'Britney Spears', '4:05', 'Pop'),
(6, 6, 'From the Bottom of My Broken Heart', 'Britney Spears', '5:11', 'Pop');

INSERT IGNORE INTO SPECIALIZATION (DJ, GENRE)
VALUES
(7, 'Electronic Pop'),
(7, 'Dance Pop'),
(7, 'Trip Hop'),
(8, 'Metal'),
(9, 'Indie Rock'),
(9, 'Art Pop'),
(10, 'R&B'),
(10, 'House'),
(10, 'Pop');

INSERT IGNORE INTO SPECIALIZES (SUBGENRE, GENRE)
VALUES
    ('Pop rock', 'Pop'),
    ('Rap', 'Hip Hop'),
    ('House', 'Electronic'),
    ('Bluegrass', 'Country'),
    ('Indie folk', 'Folk'),
    ('Heavy Metal', 'Metal'),
    ('Electronic Pop', 'Pop'),
    ('Electronic Pop', 'Electronic'),
    ('Art Pop', 'Pop'),
    ('Indie Rock', 'Rock'),
    ('Trip Hop', 'Dance'),
    ('Trip Hop', 'Hip Hop'),
    ('Dance Pop', 'Pop'),
    ('Dance Pop', 'Dance');

INSERT IGNORE INTO SUITABLEFOR (THEME, PLAYLIST)
VALUES 
('Vampires', 'Vampire Mood'),
('Vampires', 'Spooky'),
('Vampires', 'Fantasy 1'),
('Lord of the Rings', 'Fantasy 1'),
('Lord of the Rings', 'Fantasy 2'),
('Mean Girls', 'Generic Playlist'),
('Clueless', 'Generic Playlist'),
('Mean Girls', 'Basic Playlist'),
('Tropical', 'Summer Vibes'),
('Tropical', 'Beach');

INSERT IGNORE INTO SUPERVISION (SUPERVISOR_ID, EMPLOYEE_ID) 
VALUES (1,3), (1,4), (1,7), (1,8), (2,5), (2,6), (2,9), (2,10);

INSERT IGNORE INTO THEME (NAME) 
VALUES
('Vampires'),
('Lord of the Rings'),
('Tropical'),
('Mean Girls'),
('Clueless'),
('Matrix');

COMMIT;
