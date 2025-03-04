CREATE TABLE Usuarios  (
    Correo varchar(255) PRIMARY KEY,
    Nombre varchar(255),
    Apellidos varchar(255),
    Contrasena VARCHAR(500),
    Verificado VARCHAR(10)
);
INSERT INTO usuarios Values(
    "victorjosecorralguillot@gmail.com",
    "Victor Jose",
    "Corral Guillot",
    "$2y$10$d.H/4U6Gy0kgqx0x3OKxr.I0b9mJ0tOZuzTU1ljkS3eHWsQkKKata",
    "True"
)