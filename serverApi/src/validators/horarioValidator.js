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
    body('dia_semana')
        .notEmpty().withMessage('El día de la semana es obligatorio'),
    body('hora_inicio')
        .notEmpty().withMessage('La hora de inicio es obligatoria'),
    body('duracion')
        .notEmpty().withMessage('La duración es obligatoria')
        .isInt().withMessage('La duración debe ser un número'),
    body('materia_id')
        .notEmpty().withMessage('La materia es obligatoria')
        .isInt().withMessage('La materia debe ser un número'),
    body('edificio_id')
        .notEmpty().withMessage('El edificio es obligatorio')
        .isInt().withMessage('El edificio debe ser un número'),
    validate
];

exports.validateUpdate = [
    body('codigo').optional().notEmpty().withMessage('El código no puede estar vacío'),
    body('dia_semana').optional().notEmpty().withMessage('El día de la semana no puede estar vacío'),
    body('hora_inicio').optional().notEmpty().withMessage('La hora de inicio no puede estar vacía'),
    body('duracion').optional().isInt().withMessage('La duración debe ser un número'),
    body('materia_id').optional().isInt().withMessage('La materia debe ser un número'),
    body('edificio_id').optional().isInt().withMessage('El edificio debe ser un número'),
    validate
];

exports.validateSearch = [
    query('codigo').optional(),
    query('dia_semana').optional(),
    validate
];
