-- Kreiranje tabele za učesnike trke
CREATE TABLE IF NOT EXISTS registrants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    date_of_birth DATE NOT NULL,
    contact_phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    motorcycle VARCHAR(255) NOT NULL,
    engine_displacement VARCHAR(50) NOT NULL,
    driving_license_number VARCHAR(100) NOT NULL,
    license_valid_until DATE NOT NULL,
    race_term VARCHAR(100) NOT NULL,
    starting_number INT NOT NULL UNIQUE,
    competitive_license ENUM('yes', 'no') NOT NULL,
    racing_group VARCHAR(100),
    confirmation_diabetes TINYINT(1) NOT NULL,
    confirmation_risk TINYINT(1) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed') DEFAULT 'pending',
    reference_number VARCHAR(100)
);

-- Kreiranje tabele za termine trke
CREATE TABLE IF NOT EXISTS race_terms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    term_name VARCHAR(100) NOT NULL,
    term_datetime DATETIME NOT NULL,
    price_rsd DECIMAL(10,2) NOT NULL,
    price_eur DECIMAL(10,2) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

-- Kreiranje tabele za grupe (ako je potrebno)
CREATE TABLE IF NOT EXISTS groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    term_id INT,
    group_name VARCHAR(100),
    FOREIGN KEY (term_id) REFERENCES race_terms(id) ON DELETE CASCADE
);
