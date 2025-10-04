-- SQL file for e-commerce billing system tables

-- Tabla: `sistema_usuarios`
CREATE TABLE sistema_usuarios (
    idusuario INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    clave VARCHAR(255),
    ultimo_ingreso TIMESTAMP,
    token VARCHAR(255),
    rol VARCHAR(50)
);

-- Tabla: `categoria`
CREATE TABLE categoria (
    idcategoria INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo TINYINT(1) DEFAULT 1
);

-- Tabla: `clientes`
CREATE TABLE clientes (
    idcliente INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    telefono VARCHAR(50),
    direccion VARCHAR(50),
    dni VARCHAR(50),
    correo VARCHAR(50) UNIQUE,
    clave VARCHAR(150)
);

-- Tabla: `estados`
CREATE TABLE estados (
    idestado INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(100)
);

-- Tabla: `productos`
CREATE TABLE productos (
    idproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    precio DECIMAL(10, 2) UNSIGNED,
    cantidad INT UNSIGNED,
    descripcion TEXT,
    imagen VARCHAR(255),
    fk_categoria INT UNSIGNED,
    fk_usuario INT UNSIGNED,
    FOREIGN KEY (fk_categoria) REFERENCES categoria(idcategoria),
    FOREIGN KEY (fk_usuario) REFERENCES sistema_usuarios(idusuario)
);

-- Tabla: `carritos`
CREATE TABLE carritos (
    idcarrito INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_cliente INT UNSIGNED,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (fk_cliente) REFERENCES clientes(idcliente)
);

-- Tabla: `carritoProductos`
CREATE TABLE carritoProductos (
    idcarritoproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_carrito INT UNSIGNED,
    fk_producto INT UNSIGNED,
    cantidad INT UNSIGNED DEFAULT 1,
    FOREIGN KEY (fk_carrito) REFERENCES carritos(idcarrito),
    FOREIGN KEY (fk_producto) REFERENCES productos(idproducto)
);

-- Tabla: `pedidos`
CREATE TABLE pedidos (
    idpedido INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_cliente INT UNSIGNED,
    fk_estado INT UNSIGNED,
    fecha DATE,
    total DECIMAL(10, 2) UNSIGNED,
    FOREIGN KEY (fk_cliente) REFERENCES clientes(idcliente),
    FOREIGN KEY (fk_estado) REFERENCES estados(idestado)
);

-- Tabla: `pedidoproductos`
CREATE TABLE pedidoproductos (
    idpedidoproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_pedido INT UNSIGNED,
    fk_producto INT UNSIGNED,
    cantidad INT UNSIGNED,
    precio DECIMAL(10, 2) UNSIGNED,
    FOREIGN KEY (fk_pedido) REFERENCES pedidos(idpedido),
    FOREIGN KEY (fk_producto) REFERENCES productos(idproducto)
);