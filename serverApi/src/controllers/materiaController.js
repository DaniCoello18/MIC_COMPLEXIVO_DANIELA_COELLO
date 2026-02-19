const db = require('../config/db');

// Obtener todas las materias
exports.getAll = (req, res) => {
    db.query('SELECT * FROM materias', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener materia por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM materias WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Materia no encontrada' });

        res.json(results[0]);
    });
};

// Crear materia
exports.create = (req, res) => {
    const { codigo, nombre, descripcion, especialidad_id } = req.body;

    const sql = `
        INSERT INTO materias
        (codigo, nombre, descripcion, especialidad_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
    `;

    db.query(sql, [codigo, nombre, descripcion, especialidad_id], (err, result) => {
        if (err) return res.status(500).json(err);
        res.status(201).json({ message: 'Materia creada', id: result.insertId });
    });
};

// Actualizar materia
exports.update = (req, res) => {
    const { id } = req.params;
    const { codigo, nombre, descripcion, especialidad_id } = req.body;

    const sql = `
        UPDATE materias
        SET codigo = ?, nombre = ?, descripcion = ?,
            especialidad_id = ?,
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(sql, [codigo, nombre, descripcion, especialidad_id, id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Materia no encontrada' });
        res.json({ message: 'Materia actualizada' });
    });
};

// Eliminar materia
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM materias WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Materia no encontrada' });
        res.json({ message: 'Materia eliminada' });
    });
};

// Búsqueda inteligente de materias
exports.search = (req, res) => {
    const { codigo, nombre, descripcion } = req.query;
    const conditions = [];
    const params = [];

    if (codigo) {
        conditions.push('codigo LIKE ?');
        params.push(`%${codigo}%`);
    }
    if (nombre) {
        conditions.push('nombre LIKE ?');
        params.push(`%${nombre}%`);
    }
    if (descripcion) {
        conditions.push('descripcion LIKE ?');
        params.push(`%${descripcion}%`);
    }

    if (conditions.length === 0) {
        return res.status(400).json({ message: 'Se requiere al menos un criterio de búsqueda' });
    }

    const sql = `SELECT * FROM materias WHERE ${conditions.join(' OR ')}`;
    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};
