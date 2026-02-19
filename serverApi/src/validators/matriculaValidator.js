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
    body('fecha_matricula')
        .notEmpty().withMessage('La fecha es obligatoria')
        .isISO8601().withMessage('La fecha debe tener formato ISO'),
    body('alumno_id')
        .notEmpty().withMessage('El alumno es obligatorio')
        .isInt().withMessage('El alumno debe ser un número'),
    body('materia_id')
        .notEmpty().withMessage('La materia es obligatoria')
        .isInt().withMessage('La materia debe ser un número'),
    validate
];

exports.validateUpdate = [
    body('codigo').optional().notEmpty().withMessage('El código no puede estar vacío'),
    body('fecha_matricula').optional().isISO8601().withMessage('La fecha debe tener formato ISO'),
    body('alumno_id').optional().isInt().withMessage('El alumno debe ser un número'),
    body('materia_id').optional().isInt().withMessage('La materia debe ser un número'),
    validate
];

exports.validateSearch = [
    query('codigo').optional(),
    validate
];
