/* -------- PROCEDIMIENTOS ALMACENADOS PARA CATEGORIA -------- */

--PROCEDIMIENTO GUARDAR CATEGORIA
CREATE PROC SP_REGISTRARCATEGORIA(
    @Descripcion varchar(50),
    @Resultado int output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Resultado = 0

    IF NOT EXISTS (SELECT * FROM CATEGORIA WHERE Descripcion = @Descripcion)
    BEGIN
        INSERT INTO CATEGORIA (Descripcion) VALUES (@Descripcion)
        SET @Resultado = SCOPE_IDENTITY()
        SET @Mensaje = 'Categoria registrada con exito!'
    END
    ELSE
        SET @Mensaje = 'No se puede repetir la descripcion de una categoria'

END

GO

--PROCEDIMIENTO MODIFICAR CATEGORIA
CREATE PROC SP_EDITARCATEGORIA(
    @IdCategoria int,
    @Descripcion varchar(50),
    @Resultado bit output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Resultado = 1

    IF NOT EXISTS (SELECT * FROM CATEGORIA WHERE Descripcion = @Descripcion and IdCategoria != @IdCategoria)
    BEGIN
        UPDATE CATEGORIA SET
        Descripcion = @Descripcion
        WHERE IdCategoria = @IdCategoria
        SET @Mensaje = 'Categoria editada con exito!'
    END
    ELSE
    BEGIN
        SET @Resultado = 0
        SET @Mensaje = 'No se puede repetir la descripcion de una categoria'
    END

END

GO

--PROCEDIMIENTO ELIMINAR CATEGORIA
CREATE PROC SP_ELIMINARCATEGORIA(
    @IdCategoria int,
    @Resultado bit output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Resultado = 1

    IF NOT EXISTS (
        SELECT * FROM CATEGORIA c
        INNER JOIN PRODUCTO p on p.IdCategoria = c.IdCategoria
        WHERE c.IdCategoria = @IdCategoria
    )BEGIN
        DELETE TOP(1) FROM CATEGORIA WHERE IdCategoria = @IdCategoria
        SET @Mensaje = 'Categoria eliminada con exito'
    END

    ELSE
    BEGIN
        SET @Resultado = 0
        SET @Mensaje = 'La categoria se encuentra relacionada a un producto'
    END

END

/* -------- PROCEDIMIENTOS ALMACENADOS PARA PRODUCTO -------- */

--PROCEDIMIENTO GUARDAR PRODUCTO
CREATE PROC SP_REGISTRARPRODUCTO(
    @Codigo varchar(20),
    @Nombre varchar(30),
    @Descripcion varchar(50),
    @IdCategoria int,
    @Estado bit,
    @Resultado int output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Resultado = 0

    IF NOT EXISTS (SELECT * FROM PRODUCTO WHERE Codigo = @Codigo)
    BEGIN
        INSERT INTO PRODUCTO (Codigo, Nombre, Descripcion, IdCategoria, Estado) 
        VALUES (@Codigo, @Nombre, @Descripcion, @IdCategoria, @Estado)
        SET @Resultado = SCOPE_IDENTITY()
        SET @Mensaje = 'Producto registrado con exito!'
    END
    ELSE
        SET @Mensaje = 'Ya existe un producto con el mismo codigo'

END

GO

--PROCEDIMIENTO MODIFICAR PRODUCTO
CREATE PROC SP_EDITARPRODUCTO(
    @IdProducto int,
    @Codigo varchar(20),
    @Nombre varchar(30),
    @Descripcion varchar(50),
    @IdCategoria int,
    @Estado bit,
    @Resultado bit output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Resultado = 1

    IF NOT EXISTS (SELECT * FROM PRODUCTO WHERE Codigo = @Codigo and IdProducto != @IdProducto)
    BEGIN
        UPDATE PRODUCTO SET
        Codigo = @Codigo,
        Nombre = @Nombre,
        Descripcion = @Descripcion,
        IdCategoria = @IdCategoria,
        Estado = @Estado
        WHERE IdProducto = @IdProducto
        SET @Mensaje = 'Producto editado con exito!'
    END
    ELSE
    BEGIN
        SET @Resultado = 0
        SET @Mensaje = 'Ya existe un producto con el mismo codigo'
    END

END

GO

--PROCEDIMIENTO ELIMINAR PRODUCTO
CREATE PROC SP_ELIMINARPRODUCTO(
    @IdProducto int,
    @Respuesta bit output,
    @Mensaje varchar(500) output
)AS 
BEGIN

    SET @Respuesta = 0
    SET @Mensaje = ''
    declare @pasoreglas bit = 1

    IF EXISTS (
        SELECT * FROM DETALLE_COMPRA dc
        INNER JOIN PRODUCTO p on p.IdProducto = dc.IdProducto
        WHERE p.IdProducto = @IdProducto
    )BEGIN
        SET @pasoreglas = 0
        SET @Respuesta = 0
        SET @Mensaje = @Mensaje + 'No se puede eliminar porque se encuentra relacionado a una COMPRA\n'
    END

    IF EXISTS (
        SELECT * FROM DETALLE_VENTA dv
        INNER JOIN PRODUCTO p on p.IdProducto = dv.IdProducto
        WHERE p.IdProducto = @IdProducto
    )BEGIN
        SET @pasoreglas = 0
        SET @Respuesta = 0
        SET @Mensaje = @Mensaje + 'No se puede eliminar porque se encuentra relacionado a una VENTA\n'
    END

    IF(@pasoreglas = 1)
    BEGIN
        DELETE FROM PRODUCTO WHERE IdProducto = @IdProducto
        SET @Respuesta = 1
        SET @Mensaje = 'Producto elimanado con exito!'
    END

END