<?php
try {
    // Cria a base de dados
    $db = new PDO('sqlite:../database/tutormatch.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criação das tabelas
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            role TEXT DEFAULT 'client', -- client, freelancer, admin
            name TEXT,
            profile_pic TEXT DEFAULT '../img/default.jpeg' -- Adiciona a coluna profile_pic
        );
        
        CREATE TABLE IF NOT EXISTS services (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INT NOT NULL,
            category_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            delivery_time INT NOT NULL,
            image_path VARCHAR(255),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );

        DROP TABLE IF EXISTS messages; -- Adiciona esta linha para evitar conflitos
        CREATE TABLE messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            service_id INT,
            from_user_id INT,
            to_user_id INT,
            message TEXT NOT NULL,
            sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id),
            FOREIGN KEY (from_user_id) REFERENCES users(id),
            FOREIGN KEY (to_user_id) REFERENCES users(id)
        );

        DROP TABLE IF EXISTS orders; -- Adiciona esta linha para evitar conflitos


        CREATE TABLE orders (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INT,
            service_id INT,
            status TEXT DEFAULT 'pendente',
            ordered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (service_id) REFERENCES services(id)
        );

        DROP TABLE IF EXISTS reviews; -- Adiciona esta linha para evitar conflitos
        CREATE TABLE reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            service_id INT,
            user_id INT,
            rating INT CHECK (rating BETWEEN 1 AND 5),
            comment TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id),
            FOREIGN KEY (user_id) REFERENCES users(id)
        );


        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE
        );
    ");
} catch (PDOException $e) {
    echo "Erro ao criar a base de dados: " . $e->getMessage();
}
?>
