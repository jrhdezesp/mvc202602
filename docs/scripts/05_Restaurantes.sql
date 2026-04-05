-- Tabla: Datos de Restaurantes
CREATE TABLE DatosRestaurantes (
    id_restaurante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo_cocina VARCHAR(50),
    ubicacion VARCHAR(100),
    calificacion DECIMAL(3, 2),
    capacidad_comensales INT
);

-- Caso de uso para Datos de Restaurantes
-- Almacena detalles sobre restaurantes, como nombre, tipo de cocina, ubicación, calificación y capacidad de comensales.
