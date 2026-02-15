-- ============================================
-- 1. CREACIÓN DE TABLA CLIENTE
-- ============================================
CREATE TABLE cliente (
  id INT AUTO_INCREMENT,
  nombre VARCHAR(255),
  apellidos VARCHAR(255),
  email VARCHAR(255),
  telefono VARCHAR(255),
  PRIMARY KEY (id)
);

-- ============================================
-- 2. CREACIÓN DE TABLA CAMPO
-- ============================================
CREATE TABLE campo (
  id INT AUTO_INCREMENT,
  nombre VARCHAR(255),
  tipo VARCHAR(255),
  descripcion VARCHAR(255),
  precio_hora VARCHAR(255),
  imagen VARCHAR(255),
  PRIMARY KEY (id)
);

-- ============================================
-- 3. CREACIÓN DE TABLA RESERVA
-- ============================================
CREATE TABLE reserva (
  id INT AUTO_INCREMENT,
  fecha VARCHAR(255),
  cliente_id INT,
  PRIMARY KEY (id),
  CONSTRAINT fk_reserva_cliente
    FOREIGN KEY (cliente_id) REFERENCES cliente(id)
);

-- ============================================
-- 4. CREACIÓN DE TABLA LINEA RESERVA
--    (depende de reserva y campo)
-- ============================================
CREATE TABLE lineareserva (
  id INT AUTO_INCREMENT,
  reserva_id INT,
  campo_id INT,
  dia VARCHAR(255),
  hora VARCHAR(255),
  duracion VARCHAR(255),
  PRIMARY KEY (id),
  CONSTRAINT fk_lineareserva_reserva
    FOREIGN KEY (reserva_id) REFERENCES reserva(id),
  CONSTRAINT fk_lineareserva_campo
    FOREIGN KEY (campo_id) REFERENCES campo(id)
);
