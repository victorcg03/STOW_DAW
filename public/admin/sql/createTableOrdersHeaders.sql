CREATE TABLE CabeceraPedidos(
    PedidoID VARCHAR(14),
    CorreoUsuario varchar(255),
    FechaPedido DATETIME,
    SubtotalPedido DECIMAL(10,2),
    EnvioPedido DECIMAL(10,2),
    IvaPedido DECIMAL(10,2),
    TotalPedido DECIMAL(10,2),
    Completado BOOLEAN,
    PRIMARY KEY (`PedidoID`, `CorreoUsuario`),
    FOREIGN KEY (CorreoUsuario) REFERENCES usuarios(correo)
)