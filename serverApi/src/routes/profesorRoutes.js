const express = require('express');
const router = express.Router();
const profesorController = require('../controllers/profesorController');
const profesorValidator = require('../validators/profesorValidator');

router.get('/search', profesorValidator.validateSearch, profesorController.search);
router.get('/', profesorController.getAll);
router.get('/:id', profesorValidator.validateId, profesorController.getById);
router.post('/', profesorValidator.validateCreate, profesorController.create);
router.put('/:id', profesorValidator.validateId, profesorValidator.validateUpdate, profesorController.update);
router.delete('/:id', profesorValidator.validateId, profesorController.delete);

module.exports = router;
