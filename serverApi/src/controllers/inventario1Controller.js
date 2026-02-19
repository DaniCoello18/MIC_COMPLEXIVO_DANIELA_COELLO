const db = require('../config/db');

// Obtener todos los inventarios
exports.getAll = (req, res) => {
    db.query('SELECT * FROM inventario1', (err, results) => {
        if (err) return res.status(500).json(err);
        res.json(results);
    });
};