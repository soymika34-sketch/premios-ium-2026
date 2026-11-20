-- Base de datos PREMIOS IUM 2026 (según el diagrama ER)
-- Se añadieron a "usuarios": correo (único) y password_hash, necesarios para el registro.
CREATE DATABASE IF NOT EXISTS premios_ium CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE premios_ium;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS votos, nominaciones, participante, categorias, usuarios;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  cargo VARCHAR(50) NOT NULL,
  correo VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE categorias (
  id_categoria INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  imagen_categoria VARCHAR(500) NULL
) ENGINE=InnoDB;

CREATE TABLE participante (
  id_nominado INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria INT NOT NULL,
  nombre_participante VARCHAR(60) NOT NULL,
  nombre_trabajo VARCHAR(50) NOT NULL,
  imagen_trabajo VARCHAR(500) NOT NULL DEFAULT '',
  imagen_participante VARCHAR(500) NOT NULL DEFAULT '',
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
) ENGINE=InnoDB;

CREATE TABLE nominaciones (
  id_nominacion INT AUTO_INCREMENT PRIMARY KEY,
  id_categoria INT NOT NULL,
  id_nominado INT NOT NULL,
  ganador VARCHAR(50) NULL,
  UNIQUE KEY uq_cat_nom (id_categoria, id_nominado),
  FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
  FOREIGN KEY (id_nominado) REFERENCES participante(id_nominado)
) ENGINE=InnoDB;

CREATE TABLE votos (
  id_voto INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  id_nominado INT NOT NULL,
  fecha_voto DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_usuario_nominado (id_usuario, id_nominado),
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
  FOREIGN KEY (id_nominado) REFERENCES participante(id_nominado)
) ENGINE=InnoDB;

-- Datos iniciales
INSERT INTO categorias (nombre) VALUES
 ('MEJOR ESTUDIANTE'),('LIDERAZGO'),('DISCIPLINA Y VALORES'),('DEPORTISTA DESTACADO'),
 ('EXCELENCIA ACADÉMICA'),('ARTE Y CULTURA'),('COMPAÑERISMO'),('CONVIVENCIA ESCOLAR'),
 ('INNOVACIÓN Y CREATIVIDAD'),('ALEGRÍA IUM');

-- 3 participantes de ejemplo por categoría (cámbialos por los reales)
INSERT INTO participante (id_categoria, nombre_participante, nombre_trabajo)
SELECT c.id_categoria, CONCAT('Participante ', t.k), CONCAT('Trabajo ', t.k)
FROM categorias c CROSS JOIN (SELECT 1 AS k UNION SELECT 2 UNION SELECT 3) t;

INSERT INTO nominaciones (id_categoria, id_nominado)
SELECT id_categoria, id_nominado FROM participante;
