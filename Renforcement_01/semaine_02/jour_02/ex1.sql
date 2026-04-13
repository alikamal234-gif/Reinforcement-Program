CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    role ENUM('visiteur','membre','admin') DEFAULT 'membre'
);


CREATE TABLE films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100),
    duree INT
);


CREATE TABLE seances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT,
    date_seance DATETIME,
    prix DECIMAL(10, 2),
    FOREIGN KEY (film_id) REFERENCES films (id)
);
CREATE TABLE sieges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10)
);
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    seance_id INT,
    siege_id INT,
    statut ENUM('valide', 'annule'),
    date_reservation DATETIME,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (seance_id) REFERENCES seances (id),
    FOREIGN KEY (siege_id) REFERENCES sieges (id)
);
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT,
    montant DECIMAL(10, 2),
    methode VARCHAR(50),
    statut ENUM('paye', 'refuse'),
    FOREIGN KEY (reservation_id) REFERENCES reservations (id)
);