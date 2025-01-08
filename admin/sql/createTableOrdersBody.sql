DROP TABLE IF EXISTS CuerpoPedidos;
CREATE TABLE CuerpoPedidos(
    PedidoID VARCHAR(14),
    ProductoID VARCHAR(10),
    ProductoTalla VARCHAR(2),
    Cantidad INTEGER,
    Precio DECIMAL(10,2),
    PRIMARY KEY (`PedidoID`, `ProductoID`, `ProductoTalla`),
    FOREIGN KEY (PedidoID) REFERENCES CabeceraPedidos(PedidoID),
    FOREIGN KEY (ProductoID) REFERENCES Productos(ID)
)