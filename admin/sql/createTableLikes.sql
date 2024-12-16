DROP TABLE IF EXISTS Likes;
CREATE TABLE Likes(
    ProductoID VARCHAR(10),
    Usuario varchar(255),
    PRIMARY KEY (`ProductoID`, `Usuario`),
    FOREIGN KEY (ProductoID) REFERENCES productos(ID),
    FOREIGN KEY (Usuario) REFERENCES usuarios(correo)
)