DROP TABLE IF EXISTS Usuarios;
CREATE TABLE Usuarios  (
    Correo varchar(255) PRIMARY KEY,
    Nombre varchar(255),
    Apellidos varchar(255),
    Contrasena VARCHAR(500),
    Verificado VARCHAR(10)
);