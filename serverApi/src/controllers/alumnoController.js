const db = require('../config/db');

// Obtener todos los alumnos
exports.getAll = (req, res) => {
    db.query('SELECT * FROM alumnos', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener alumno por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM alumnos WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Alumno no encontrado' });

        res.json(results[0]);
    });
};

// Crear alumno
exports.create = (req, res) => {
    const {
        cedula,
        nombre,
        apellido,
        fecha_nacimiento,
        direccion,
        correo
    } = req.body;

    const sql = `
        INSERT INTO alumnos 
        (cedula, nombre, apellido, fecha_nacimiento, direccion, correo, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    `;

    db.query(
        sql,
        [cedula, nombre, apellido, fecha_nacimiento, direccion, correo],
        (err, result) => {
            if (err) return res.status(500).json(err);

            res.status(201).json({
                message: 'Alumno creado',
                id: result.insertId
            });
        }
    );
};

// Actualizar alumno
exports.update = (req, res) => {
    const { id } = req.params;
    const {
        cedula,
        nombre,
        apellido,
        fecha_nacimiento,
        direccion,
        correo
    } = req.body;

    const sql = `
        UPDATE alumnos
        SET cedula = ?, nombre = ?, apellido = ?, 
            fecha_nacimiento = ?, direccion = ?, correo = ?, 
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(
        sql,
        [cedula, nombre, apellido, fecha_nacimiento, direccion, correo, id],
        (err, result) => {
            if (err) return res.status(500).json(err);
            if (result.affectedRows === 0)
                return res.status(404).json({ message: 'Alumno no encontrado' });

            res.json({ message: 'Alumno actualizado' });
        }
    );
};

// Eliminar alumno
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM alumnos WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Alumno no encontrado' });

        res.json({ message: 'Alumno eliminado' });
    });
};


// Buscar alumnos por criterios inteligentes
exports.search = (req, res) => {
    const { cedula, nombre, apellido, correo } = req.query;

    const conditions = [];
    const params = [];

    if (cedula) {
        conditions.push('cedula LIKE ?');
        params.push(`%${cedula}%`);
    }
    if (nombre) {
        conditions.push('nombre LIKE ?');
        params.push(`%${nombre}%`);
    }
    if (apellido) {
        conditions.push('apellido LIKE ?');
        params.push(`%${apellido}%`);
    }
    if (correo) {
        conditions.push('correo LIKE ?');
        params.push(`%${correo}%`);
    }

    if (conditions.length === 0) {
        return res.status(400).json({ message: 'Se requiere al menos un criterio de búsqueda' });
    }

    const sql = `SELECT * FROM alumnos WHERE ${conditions.join(' OR ')}`;

    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};
