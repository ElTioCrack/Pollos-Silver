DELIMITER //

CREATE PROCEDURE CreateUserAndEmployee(
    IN p_ci INT,
    IN p_contrasena TEXT,
    IN p_id_sucursal INT,
    IN p_nombres VARCHAR(100),
    IN p_apellidos VARCHAR(100),
    IN p_telefono VARCHAR(10),
    IN p_estado INT,
    OUT p_creado INT,
    OUT p_mensaje VARCHAR(100)
)
BEGIN
    DECLARE ci_count INT;

    -- Verificar si el ci ya existe en la tabla usuarios
    SELECT COUNT(*) INTO ci_count FROM usuarios WHERE ci = p_ci;

    IF ci_count > 0 THEN
        SET p_creado = 0;
        SET p_mensaje = 'El número de identificación ya existe en la tabla usuarios.';
    ELSE
        -- Insertar datos en la tabla usuarios
        INSERT INTO usuarios (ci, contrasena, id_tipousuario) VALUES (p_ci, p_contrasena, 3);
        
        -- Insertar datos en la tabla empleados
        INSERT INTO empleados (ci, id_sucursal, nombres, apellidos, telefono, estado)
        VALUES (p_ci, p_id_sucursal, p_nombres, p_apellidos, p_telefono, p_estado);
        
        SET p_creado = 1;
        SET p_mensaje = 'Registro creado correctamente.';
    END IF;
END//

DELIMITER ;
