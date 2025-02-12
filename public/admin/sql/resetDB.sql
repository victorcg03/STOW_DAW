DROP TABLE IF EXISTS CuerpoPedidos;

DROP TABLE IF EXISTS CabeceraPedidos;

DROP TABLE IF EXISTS Likes;

DROP TABLE IF EXISTS Productos;

DROP TABLE IF EXISTS Usuarios;

CREATE TABLE Usuarios (
    Correo varchar(255) PRIMARY KEY,
    Nombre varchar(255),
    Apellidos varchar(255),
    Contrasena VARCHAR(500),
    Verificado VARCHAR(10)
);

CREATE TABLE Productos (
    ID VARCHAR(10) PRIMARY KEY,
    Nombre VARCHAR(255),
    Sexo VARCHAR(30),
    Color VARCHAR(25),
    ClaseProducto VARCHAR(20),
    Stock VARCHAR(255),
    Precio DECIMAL(10, 2),
    Imagenes TEXT, 
    activo INT DEFAULT 1
);

CREATE TABLE Likes (
    ProductoID VARCHAR(10),
    Usuario varchar(255),
    PRIMARY KEY (`ProductoID`, `Usuario`),
    FOREIGN KEY (ProductoID) REFERENCES productos (ID),
    FOREIGN KEY (Usuario) REFERENCES usuarios (correo)
);

CREATE TABLE CabeceraPedidos (
    PedidoID VARCHAR(14),
    CorreoUsuario varchar(255),
    FechaPedido DATETIME,
    SubtotalPedido DECIMAL(10, 2),
    EnvioPedido DECIMAL(10, 2),
    IvaPedido DECIMAL(10, 2),
    TotalPedido DECIMAL(10, 2),
    Completado VARCHAR(10),
    PRIMARY KEY (`PedidoID`, `CorreoUsuario`),
    FOREIGN KEY (CorreoUsuario) REFERENCES usuarios (correo)
);

CREATE TABLE CuerpoPedidos (
    PedidoID VARCHAR(14),
    ProductoID VARCHAR(10),
    ProductoTalla VARCHAR(2),
    Cantidad INTEGER,
    Precio DECIMAL(10, 2),
    PRIMARY KEY (
        `PedidoID`,
        `ProductoID`,
        `ProductoTalla`
    ),
    FOREIGN KEY (PedidoID) REFERENCES CabeceraPedidos (PedidoID),
    FOREIGN KEY (ProductoID) REFERENCES Productos (ID)
);