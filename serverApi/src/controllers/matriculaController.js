const db = require('../config/db');

// Obtener todas las matrículas
exports.getAll = (req, res) => {
    db.query('SELECT * FROM matriculas', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener matrícula por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM matriculas WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Matrícula no encontrada' });

        res.json(results[0]);
    });
};

// Crear matrícula
exports.create = (req, res) => {
    const { codigo, fecha_matricula, alumno_id, materia_id } = req.body;

    const sql = `
        INSERT INTO matriculas
        (codigo, fecha_matricula, alumno_id, materia_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, NOW(), NOW())
    `;

    db.query(sql, [codigo, fecha_matricula, alumno_id, materia_id], (err, result) => {
        if (err) return res.status(500).json(err);
        res.status(201).json({ message: 'Matrícula creada', id: result.insertId });
    });
};

// Actualizar matrícula
exports.update = (req, res) => {
    const { id } = req.params;
    const { codigo, fecha_matricula, alumno_id, materia_id } = req.body;

    const sql = `
        UPDATE matriculas
        SET codigo = ?, fecha_matricula = ?, alumno_id = ?,
            materia_id = ?,
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(sql, [codigo, fecha_matricula, alumno_id, materia_id, id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Matrícula no encontrada' });
        res.json({ message: 'Matrícula actualizada' });
    });
};

// Eliminar matrícula
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM matriculas WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Matrícula no encontrada' });
        res.json({ message: 'Matrícula eliminada' });
    });
};

// Búsqueda inteligente de matrículas
exports.search = (req, res) => {
    const { codigo } = req.query;
    const conditions = [];
    const params = [];

    if (codigo) {
        conditions.push('codigo LIKE ?');
        params.push(`%${codigo}%`);
    }

    if (conditions.length === 0) {
        return res.status(400).json({ message: 'Se requiere al menos un criterio de búsqueda' });
    }

    const sql = `SELECT * FROM matriculas WHERE ${conditions.join(' OR ')}`;
    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};
