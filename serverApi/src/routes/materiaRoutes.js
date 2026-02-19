const express = require('express');
const router = express.Router();
const materiaController = require('../controllers/materiaController');
const materiaValidator = require('../validators/materiaValidator');

router.get('/search', materiaValidator.validateSearch, materiaController.search);
router.get('/', materiaController.getAll);
router.get('/:id', materiaValidator.validateId, materiaController.getById);
router.post('/', materiaValidator.validateCreate, materiaController.create);
router.put('/:id', materiaValidator.validateId, materiaValidator.validateUpdate, materiaController.update);
router.delete('/:id', materiaValidator.validateId, materiaController.delete);

module.exports = router;
