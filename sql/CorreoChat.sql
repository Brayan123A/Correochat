CREATE DATABASE IF NOT EXISTS correochat
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE correochat;

CREATE TABLE usuarios (
    id_usuario INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) DEFAULT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    foto_perfil VARCHAR(255) DEFAULT 'default.png',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo','suspendido','eliminado') DEFAULT 'activo'
) ENGINE=InnoDB;

ALTER TABLE usuarios
ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL,
ADD COLUMN foto_portada VARCHAR(255) DEFAULT NULL;

CREATE TABLE sesiones (
    id_sesion INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultima_actividad TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_sesiones_usuario
        FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS publicaciones;

CREATE TABLE publicaciones (
    id_publicacion INT NOT NULL AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    video VARCHAR(255) DEFAULT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id_publicacion),

    INDEX idx_publicaciones_usuario (id_usuario)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;