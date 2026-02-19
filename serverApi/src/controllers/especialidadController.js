const db = require('../config/db');

// Obtener todos los alumnos
exports.getAll = (req, res) => {
    db.query('SELECT * FROM especialidades', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};

// Obtener alumno por ID
exports.getById = (req, res) => {
    const { id } = req.params;

    db.query('SELECT * FROM especialidades WHERE id = ?', [id], (err, results) => {
        if (err) return res.status(500).json(err);
        if (results.length === 0)
            return res.status(404).json({ message: 'Especialidad no encontrada' });

        res.json(results[0]);
    });
};

// Crear alumno
exports.create = (req, res) => {
    const {
        codigo,
        nombre,
    } = req.body;

    const sql = `
        INSERT INTO especialidades 
        (codigo, nombre, created_at, updated_at)
        VALUES (?, ?, NOW(), NOW())
    `;

    db.query(
        sql,
        [codigo, nombre],
        (err, result) => {
            if (err) return res.status(500).json(err);

            res.status(201).json({
                message: 'Especialidad creada',
                id: result.insertId
            });
        }
    );
};

// Actualizar especialidad
exports.update = (req, res) => {
    const { id } = req.params;
    const {
        codigo,
        nombre
    } = req.body;

    const sql = `
        UPDATE especialidades
        SET codigo = ?, nombre = ?, 
            updated_at = NOW()
        WHERE id = ?
    `;

    db.query(
        sql,
        [codigo, nombre, id],
        (err, result) => {
            if (err) return res.status(500).json(err);
            if (result.affectedRows === 0)
                return res.status(404).json({ message: 'Especialidad no encontrada' });

            res.json({ message: 'Especialidad actualizada' });
        }
    );
};

// Eliminar especialidad
exports.delete = (req, res) => {
    const { id } = req.params;

    db.query('DELETE FROM especialidades WHERE id = ?', [id], (err, result) => {
        if (err) return res.status(500).json(err);
        if (result.affectedRows === 0)
            return res.status(404).json({ message: 'Especialidad no encontrada' });

        res.json({ message: 'Especialidad eliminada' });
    });
};


// Buscar especialidades por criterios inteligentes
exports.search = (req, res) => {
    const { codigo, nombre } = req.query;

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
    if (conditions.length === 0) {
        return res.status(400).json({ message: 'Se requiere al menos un criterio de búsqueda' });
    }

    const sql = `SELECT * FROM especialidades WHERE ${conditions.join(' OR ')}`;

    db.query(sql, params, (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};
