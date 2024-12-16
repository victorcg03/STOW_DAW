DROP TABLE Productos;
CREATE TABLE Productos (
    ID VARCHAR(10) PRIMARY KEY,
    Nombre VARCHAR(255),
    Sexo VARCHAR(10),
    Color VARCHAR(25),
    ClaseProducto VARCHAR(20),
    Stock VARCHAR(255),
    Precio DECIMAL(10,2),
    Imagenes TEXT
)