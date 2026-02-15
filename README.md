# Reserva de Campos de Fútbol (PHP + MySQL)

Proyecto web sencillo para **reservar campos deportivos** (fútbol 7 y fútbol sala) con un flujo completo de front:
**catálogo → detalle → carrito → confirmación**, usando **PHP**, **MySQL** y **sesiones**.

## Funcionalidades

- **Catálogo de campos** (nombre, tipo, descripción, precio/hora, imagen).
- **Detalle de campo** con:
  - Selector de **fecha**
  - Selector de **hora** (09:00–21:00)
  - Selector de **duración** (1 o 2 horas)
  - **Horas ocupadas** deshabilitadas según reservas existentes en BD
  - Mensaje de **“siguiente disponible”** si el día está completo
- **Carrito en sesión**:
  - Guarda `campo_id`, `día`, `hora` y `duración`
  - Permite **eliminar** ítems
  - Botón **vaciar carrito**
  - Botón `+` para aumentar duración (máx. 2)
- **Confirmación de reserva**:
  - Formulario de datos del cliente
  - **Validación en servidor** (anti doble-reserva)
  - Regla duración 2h:
    - No permite empezar a las **21:00**
    - Comprueba que la **hora siguiente** esté libre
  - Inserta en BD:
    - `cliente`
    - `reserva`
    - `lineareserva`

## Tecnologías

- PHP (procedural)
- MySQL (mysqli)
- HTML + CSS
- Sesiones PHP (`$_SESSION`)

## Estructura del proyecto

```

/
├─ index.php
├─ css/
│  └─ estilo.css
├─ img/
│  ├─ heroe.png
│  └─ (imágenes de campos)
└─ inc/
├─ catalogo.php
├─ campo.php
├─ carrito.php
└─ finalizacion.php

````

## Requisitos

- XAMPP / WAMP / LAMP con:
  - PHP 8+ recomendado
  - MySQL/MariaDB
- Un navegador web

## Instalación

1. Copia el proyecto dentro de tu servidor local, por ejemplo:
   - `C:\xampp\htdocs\reserva_campos\`

2. Crea la base de datos y las tablas (ver script abajo).

3. Ajusta las credenciales si lo necesitas (están en los archivos `inc/*.php`):
   - host: `localhost`
   - user: `reserva_empresa`
   - pass: `Reservaempresa123_`
   - db: `reserva_empresa`

4. Abre en el navegador:
   - `http://localhost/reserva_campos/`

## Modelo de datos (SQL)

```sql
CREATE TABLE cliente (
  id INT AUTO_INCREMENT,
  nombre VARCHAR(255),
  apellidos VARCHAR(255),
  email VARCHAR(255),
  telefono VARCHAR(255),
  PRIMARY KEY (id)
);

CREATE TABLE campo (
  id INT AUTO_INCREMENT,
  nombre VARCHAR(255),
  tipo VARCHAR(255),
  descripcion VARCHAR(255),
  precio_hora VARCHAR(255),
  imagen VARCHAR(255),
  PRIMARY KEY (id)
);

CREATE TABLE reserva (
  id INT AUTO_INCREMENT,
  fecha VARCHAR(255),
  cliente_id INT,
  PRIMARY KEY (id),
  CONSTRAINT fk_reserva_cliente
    FOREIGN KEY (cliente_id) REFERENCES cliente(id)
);

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
````

## Cómo funciona el flujo

1. **Catálogo** (`inc/catalogo.php`)

   * Lista campos desde la tabla `campo`.

2. **Detalle** (`inc/campo.php`)

   * Permite elegir fecha, hora y duración.
   * Consulta `lineareserva` para deshabilitar horas ocupadas.

3. **Carrito** (`inc/carrito.php`)

   * Guarda las selecciones en `$_SESSION["carrito"]`.
   * Muestra la tabla del carrito y el formulario del cliente.

4. **Finalización** (`inc/finalizacion.php`)

   * Valida datos del cliente.
   * Revalida disponibilidad en BD (anti doble-reserva).
   * Inserta `cliente`, `reserva` y `lineareserva`.
   * Vacía el carrito tras confirmar.

## Notas

* Este proyecto está pensado como base de aprendizaje: primero se trabaja con sesión y front, y luego se amplía con un panel de control.
* La disponibilidad de horas se basa en lo guardado en `lineareserva`.

## Autor

**PieroDev** © 2026

```
```
