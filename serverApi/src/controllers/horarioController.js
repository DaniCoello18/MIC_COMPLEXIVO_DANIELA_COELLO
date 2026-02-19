const db = require('../config/db');

// Obtener todos los horarios
exports.getAll = (req, res) => {
    db.query('SELECT * FROM horarios', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener horario por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM horarios WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Horario no encontrado' });

        res.json(results[0]);
    });
};

// Crear horario
exports.create = (req, res) => {
    const { codigo, dia_semana, hora_inicio, duracion, materia_id, edificio_id } = req.body;

    const sql = `
        INSERT INTO horarios
        (codigo, dia_semana, hora_inicio, duracion, materia_id, edificio_id, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    `;

    db.query(
        sql,
        [codigo, dia_semana, hora_inicio, duracion, materia_id, edificio_id],
        (err, result) => {
            if (err) return res.status(500).json(err);
            res.status(201).json({ message: 'Horario creado', id: result.insertId });
        }
    );
};

// Actualizar horario
exports.update = (req, res) => {
    const { id } = req.params;
    const { codigo, dia_semana, hora_inicio, duracion, materia_id, edificio_id } = req.body;

    const sql = `
        UPDATE horarios
        SET codigo = ?, dia_semana = ?, hora_inicio = ?,
            duracion = ?, materia_id = ?, edificio_id = ?,
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(
        sql,
        [codigo, dia_semana, hora_inicio, duracion, materia_id, edificio_id, id],
        (err, result) => {
            if (err) return res.status(500).json(err);
            if (result.affectedRows === 0)
                return res.status(404).json({ message: 'Horario no encontrado' });
            res.json({ message: 'Horario actualizado' });
        }
    );
};

// Eliminar horario
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM horarios WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Horario no encontrado' });
        res.json({ message: 'Horario eliminado' });
    });
};

// Búsqueda inteligente de horarios
exports.search = (req, res) => {
    const { codigo, dia_semana } = req.query;
    const conditions = [];
    const params = [];

    if (codigo) {
        conditions.push('codigo LIKE ?');
        params.push(`%${codigo}%`);
    }
    if (dia_semana) {
        conditions.push('dia_semana LIKE ?');
        params.push(`%${dia_semana}%`);
    }

    if (conditions.length === 0) {
        return res.status(400).json({ message: 'Se requiere al menos un criterio de búsqueda' });
    }

    const sql = `SELECT * FROM horarios WHERE ${conditions.join(' OR ')}`;
    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};
