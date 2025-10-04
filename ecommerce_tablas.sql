-- SQL file for e-commerce tables

-- Tabla: `tipo_producto`
CREATE TABLE tipo_producto (
    idtipoproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    descripcion TEXT
);

-- Tabla: `estado_pedidos`
CREATE TABLE estado_pedidos (
    idestadopedido INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)
);

-- Tabla: `sistema_patentes`
CREATE TABLE sistema_patentes (
    idpatente INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    submodulo VARCHAR(50),
    nombre VARCHAR(50),
    acceso VARCHAR(50),
    log_operacion VARCHAR(50),
    descripcion VARCHAR(50)
);

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

-- Tabla: `productos`
CREATE TABLE productos (
    idproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(50),
    precio DECIMAL(10, 2) UNSIGNED,
    cantidad INT UNSIGNED,
    descripcion TEXT,
    imagen VARCHAR(255),
    fk_tipoproducto INT UNSIGNED,
    fk_usuario INT UNSIGNED,
    FOREIGN KEY (fk_tipoproducto) REFERENCES tipo_producto(idtipoproducto),
    FOREIGN KEY (fk_usuario) REFERENCES sistema_usuarios(idusuario)
);

-- Tabla: `pedidos`
CREATE TABLE pedidos (
    idpedido INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_cliente INT UNSIGNED,
    fk_idestadopedido INT UNSIGNED,
    fk_usuario INT UNSIGNED,
    fecha DATE,
    total INT UNSIGNED,
    FOREIGN KEY (fk_cliente) REFERENCES clientes(idcliente),
    FOREIGN KEY (fk_idestadopedido) REFERENCES estado_pedidos(idestadopedido),
    FOREIGN KEY (fk_usuario) REFERENCES sistema_usuarios(idusuario)
);

-- Tabla: `carrito`
CREATE TABLE carrito (
    idcarrito INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_cliente INT UNSIGNED,
    fk_producto INT UNSIGNED,
    FOREIGN KEY (fk_cliente) REFERENCES clientes(idcliente),
    FOREIGN KEY (fk_producto) REFERENCES productos(idproducto)
);

-- Tabla: `pedido_productos`
CREATE TABLE pedido_productos (
    idpedidoproducto INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fk_idproducto INT UNSIGNED,
    fk_idpedido INT UNSIGNED,
    FOREIGN KEY (fk_idproducto) REFERENCES productos(idproducto),
    FOREIGN KEY (fk_idpedido) REFERENCES pedidos(idpedido)
);

-- Tabla: `sistema_menus`
CREATE TABLE sistema_menus (
    idmenu INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    orden INT(11),
    nombre VARCHAR(200),
    id_padre INT(11),
    fk_idpatente INT(11),
    css VARCHAR(20),
    activo TINYINT(1),
    FOREIGN KEY (fk_idpatente) REFERENCES sistema_patentes(idpatente)
);

-- Tabla: `postulaciones`
CREATE TABLE postulaciones (
    idpostulacion INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    apellido VARCHAR(50),
    nombre VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    celular VARCHAR(50),
    whatapp VARCHAR(50),
    como VARCHAR(50),
    descripcion TEXT
);