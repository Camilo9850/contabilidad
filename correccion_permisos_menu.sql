-- Corrección de permisos para MENUCONSULTA
-- Este script asigna la patente MENUCONSULTA a familias/grupos de usuarios que necesiten acceso al menú

-- Verificar si la patente MENUCONSULTA ya está asignada a otros grupos
-- Patente MENUCONSULTA tiene idpatente = 70

-- Asignar MENUCONSULTA al grupo 'Administrativo' (idfamilia = 4)
INSERT IGNORE INTO sistema_patente_familia (fk_idpatente, fk_idfamilia) 
VALUES (70, 4);

-- Asignar MENUCONSULTA al grupo 'Usuario' (idfamilia = 5)
INSERT IGNORE INTO sistema_patente_familia (fk_idpatente, fk_idfamilia) 
VALUES (70, 5);

-- Para asegurar que los usuarios que no estaban recibiendo el permiso ahora lo tengan,
-- también verificaremos que el usuario administrador tenga todos los permisos necesarios
-- Asegurar que el usuario admin (idusuario = 1) tenga acceso a través de la familia admin (idfamilia = 1)
-- Esto ya está en la base de datos actual: INSERT INTO `sistema_usuario_familia` VALUES (1, 1, 1);

-- Para verificar que la patente MENUCONSULTA exista con los datos correctos
-- (Ya existe según base_de_datos.sql: idpatente=70, nombre='MENUCONSULTA')
SELECT * FROM sistema_patentes WHERE nombre = 'MENUCONSULTA';

-- Mostrar las asignaciones actuales de MENUCONSULTA
SELECT 
    p.nombre AS patente_nombre,
    f.nombre AS familia_nombre,
    f.descripcion AS familia_descripcion
FROM sistema_patente_familia pf
JOIN sistema_patentes p ON p.idpatente = pf.fk_idpatente
JOIN sistema_familias f ON f.idfamilia = pf.fk_idfamilia
WHERE p.nombre = 'MENUCONSULTA';