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
    body('cedula')
        .notEmpty().withMessage('La cédula es obligatoria')
        .isLength({ min: 10, max: 10 }).withMessage('La cédula debe tener 10 dígitos'),

    body('nombre')
        .notEmpty().withMessage('El nombre es obligatorio'),

    body('apellido')
        .notEmpty().withMessage('El apellido es obligatorio'),

    body('correo')
        .notEmpty().withMessage('El correo es obligatorio')
        .isEmail().withMessage('Debe ser un correo válido'),

    body('especialidad_id')
        .notEmpty().withMessage('La especialidad es obligatoria')
        .isInt().withMessage('La especialidad debe ser un número'),

    validate
];

exports.validateUpdate = [
    body('cedula').optional().isLength({ min: 10, max: 10 }).withMessage('La cédula debe tener 10 dígitos'),
    body('nombre').optional().notEmpty().withMessage('El nombre no puede estar vacío'),
    body('apellido').optional().notEmpty().withMessage('El apellido no puede estar vacío'),
    body('correo').optional().isEmail().withMessage('Debe ser un correo válido'),
    body('especialidad_id').optional().isInt().withMessage('La especialidad debe ser un número'),
    validate
];

exports.validateSearch = [
    query('cedula').optional(),
    query('nombre').optional(),
    query('apellido').optional(),
    query('correo').optional(),
    validate
];
