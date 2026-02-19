const express = require('express');
const router = express.Router();

const alumnoController = require('../controllers/alumnoController');
const alumnoValidator = require('../validators/alumnoValidator');

router.get(
    '/search',
    alumnoValidator.validateSearch,
    alumnoController.search
);

router.get(
    '/',
    alumnoController.getAll
);

router.get(
    '/:id',
    alumnoValidator.validateId,
    alumnoController.getById
);

router.post(
    '/',
    alumnoValidator.validateCreate,
    alumnoController.create
);

router.put(
    '/:id',
    alumnoValidator.validateId,
    alumnoValidator.validateUpdate,
    alumnoController.update
);

router.delete(
    '/:id',
    alumnoValidator.validateId,
    alumnoController.delete
);

module.exports = router;
