const db = require('../config/db');

// Obtener todos los edificios
exports.getAll = (req, res) => {
    db.query('SELECT * FROM edificios', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener edificio por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM edificios WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Edificio no encontrado' });

        res.json(results[0]);
    });
};

// Crear edificio
exports.create = (req, res) => {
    const { codigo, nombre, direccion } = req.body;

    const sql = `
        INSERT INTO edificios
        (codigo, nombre, direccion, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW())
    `;

    db.query(sql, [codigo, nombre, direccion], (err, result) => {
        if (err) return res.status(500).json(err);
        res.status(201).json({ message: 'Edificio creado', id: result.insertId });
    });
};

// Actualizar edificio
exports.update = (req, res) => {
    const { id } = req.params;
    const { codigo, nombre, direccion } = req.body;

    const sql = `
        UPDATE edificios
        SET codigo = ?, nombre = ?, direccion = ?,
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(sql, [codigo, nombre, direccion, id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Edificio no encontrado' });
        res.json({ message: 'Edificio actualizado' });
    });
};

// Eliminar edificio
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM edificios WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Edificio no encontrado' });
        res.json({ message: 'Edificio eliminado' });
    });
};

// Búsqueda inteligente de edificios
// Buscar edificios con un solo campo (q)
exports.search = (req, res) => {
    const { q } = req.query;

    if (!q) {
        return res.status(400).json({
            message: 'Debe enviar un valor de búsqueda'
        });
    }

    const sql = `
        SELECT * FROM edificios
        WHERE codigo LIKE ?
        OR nombre LIKE ?
        OR direccion LIKE ?
    `;

    const searchValue = `%${q}%`;

    db.query(
        sql,
        [searchValue, searchValue, searchValue],
        (err, results) => {
            if (err) return res.status(500).json(err);
            res.json(results);
        }
    );
};

