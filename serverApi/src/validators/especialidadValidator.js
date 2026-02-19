const { body, param, query, validationResult } = require('express-validator');

const validate = (req, res, next) => {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
        return res.status(422).json({
            message: 'Errores de validación',
            errors: errors.array()
        });
    }
    next();
};

exports.validateId = [
    param('id')
        .isInt().withMessage('El ID debe ser numérico'),
    validate
];

exports.validateCreate = [
    body('codigo')
        .notEmpty().withMessage('El código es obligatorio'),
    body('nombre')
        .notEmpty().withMessage('El nombre es obligatorio'),
    validate
];

exports.validateUpdate = [
    body('codigo').optional().notEmpty().withMessage('El código no puede estar vacío'),
    body('nombre').optional().notEmpty().withMessage('El nombre no puede estar vacío'),
    validate
];

exports.validateSearch = [
    query('codigo').optional(),
    query('nombre').optional(),
    validate
];
