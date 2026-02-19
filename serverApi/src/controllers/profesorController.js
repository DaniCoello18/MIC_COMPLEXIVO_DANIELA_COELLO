const db = require('../config/db');

// Obtener todos los profesores
exports.getAll = (req, res) => {
    db.query('SELECT * FROM profesores', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener profesor por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM profesores WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Profesor no encontrado' });

        res.json(results[0]);
    });
};

// Crear profesor
exports.create = (req, res) => {
    const { cedula, nombre, apellido, correo, especialidad_id } = req.body;

    const sql = `
        INSERT INTO profesores
        (cedula, nombre, apellido, correo, especialidad_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    `;

    db.query(sql, [cedula, nombre, apellido, correo, especialidad_id], (err, result) => {
        if (err) return res.status(500).json(err);
        res.status(201).json({ message: 'Profesor creado', id: result.insertId });
    });
};

// Actualizar profesor
exports.update = (req, res) => {
    const { id } = req.params;
    const { cedula, nombre, apellido, correo, especialidad_id } = req.body;

    const sql = `
        UPDATE profesores
        SET cedula = ?, nombre = ?, apellido = ?,
            correo = ?, especialidad_id = ?,
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(sql, [cedula, nombre, apellido, correo, especialidad_id, id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Profesor no encontrado' });
        res.json({ message: 'Profesor actualizado' });
    });
};

// Eliminar profesor
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM profesores WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Profesor no encontrado' });
        res.json({ message: 'Profesor eliminado' });
    });
};

// Búsqueda inteligente de profesores
// Buscar profesores con un solo parámetro (q)
exports.search = (req, res) => {
    const { q } = req.query;

    if (!q) {
        return res.status(400).json({
            message: 'Debe enviar un valor de búsqueda'
        });
    }

    const sql = `
        SELECT * FROM profesores
        WHERE cedula LIKE ?
        OR nombre LIKE ?
        OR apellido LIKE ?
        OR correo LIKE ?
    `;

    const searchValue = `%${q}%`;

    db.query(
        sql,
        [searchValue, searchValue, searchValue, searchValue],
        (err, results) => {
            if (err) return res.status(500).json(err);
            res.json(results);
        }
    );
};
