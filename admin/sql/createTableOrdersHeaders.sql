DROP TABLE IF EXISTS CabeceraPedidos;
CREATE TABLE CabeceraPedidos(
    PedidoID VARCHAR(14),
    CorreoUsuario varchar(255),
    FechaPedido DATETIME,
    PRIMARY KEY (`PedidoID`, `CorreoUsuario`),
    FOREIGN KEY (CorreoUsuario) REFERENCES usuarios(correo)
)