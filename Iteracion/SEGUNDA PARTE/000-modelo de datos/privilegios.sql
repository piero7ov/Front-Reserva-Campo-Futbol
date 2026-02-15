CREATE USER 
'reserva_empresa'@'localhost' 
IDENTIFIED BY 'Reservaempresa123_';

GRANT USAGE ON *.* TO 'reserva_empresa'@'localhost';

ALTER USER 'reserva_empresa'@'localhost' 
REQUIRE NONE 
WITH MAX_QUERIES_PER_HOUR 0 
MAX_CONNECTIONS_PER_HOUR 0 
MAX_UPDATES_PER_HOUR 0 
MAX_USER_CONNECTIONS 0;

GRANT ALL PRIVILEGES ON `reserva_empresa`.* 
TO 'reserva_empresa'@'localhost';

FLUSH PRIVILEGES;
