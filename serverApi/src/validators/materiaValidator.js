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
    body('descripcion')
        .notEmpty().withMessage('La descripción es obligatoria'),
    body('especialidad_id')
        .notEmpty().withMessage('La especialidad es obligatoria')
        .isInt().withMessage('La especialidad debe ser un número'),
    validate
];

exports.validateUpdate = [
    body('codigo').optional().notEmpty().withMessage('El código no puede estar vacío'),
    body('nombre').optional().notEmpty().withMessage('El nombre no puede estar vacío'),
    body('descripcion').optional().notEmpty().withMessage('La descripción no puede estar vacía'),
    body('especialidad_id').optional().isInt().withMessage('La especialidad debe ser un número'),
    validate
];

exports.validateSearch = [
    query('codigo').optional(),
    query('nombre').optional(),
    query('descripcion').optional(),
    validate
];
