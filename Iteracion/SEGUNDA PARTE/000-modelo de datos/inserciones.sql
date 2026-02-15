/* =========================
   CAMPOS (4)
   - 2 fútbol 7 césped sintético
   - 2 fútbol sala
   ========================= */

INSERT INTO campo (nombre, tipo, descripcion, precio_hora, imagen) VALUES
('Campo Fútbol 7 - A', 'futbol7', 'Césped sintético, ideal para pachangas y ligas.', '70', 'img/campo.png'),
('Campo Fútbol 7 - B', 'futbol7', 'Césped sintético, segundo campo de fútbol 7.', '70', 'img/campo.png'),
('Campo Fútbol Sala - 1', 'futbol_sala', 'Pista de fútbol sala, rápida y técnica.', '70', 'img/campo_sala.png'),
('Campo Fútbol Sala - 2', 'futbol_sala', 'Segunda pista de fútbol sala para más disponibilidad.', '70', 'img/campo_sala.png');


/* =========================
   CLIENTES (5)
   ========================= */

INSERT INTO cliente (nombre, apellidos, email, telefono) VALUES
('Piero', 'Olivares', 'piero@email.com', '600111222'),
('Ana', 'García', 'ana@email.com', '600222333'),
('Luis', 'Pérez', 'luis@email.com', '600333444'),
('Marta', 'Ruiz', 'marta@email.com', '600444555'),
('Jorge', 'Sánchez', 'jorge@email.com', '600555666');


/* =========================
   RESERVAS (5)
   ========================= */

INSERT INTO reserva (fecha, cliente_id) VALUES
('2026-02-14', 1),
('2026-02-15', 2),
('2026-02-15', 3),
('2026-02-16', 4),
('2026-02-16', 5);


/* =========================
   LÍNEAS DE RESERVA (5)
   - horario entre 09:00 y 21:00
   - incluye algunas de 2 horas
   ========================= */

INSERT INTO lineareserva (reserva_id, campo_id, dia, hora, duracion) VALUES
(1, 1, '2026-02-18', '09:00', '1'),
(2, 3, '2026-02-18', '11:00', '2'),
(3, 2, '2026-02-19', '17:00', '1'),
(4, 4, '2026-02-19', '19:00', '2'),
(5, 1, '2026-02-20', '20:00', '1');
