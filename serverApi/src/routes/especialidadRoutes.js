const express = require('express');
const router = express.Router();
const especialidadController = require('../controllers/especialidadController');
const especialidadValidator = require('../validators/especialidadValidator');

router.get('/search', especialidadValidator.validateSearch, especialidadController.search);
router.get('/', especialidadController.getAll);
router.get('/:id', especialidadValidator.validateId, especialidadController.getById);
router.post('/', especialidadValidator.validateCreate, especialidadController.create);
router.put('/:id', especialidadValidator.validateId, especialidadValidator.validateUpdate, especialidadController.update);
router.delete('/:id', especialidadValidator.validateId, especialidadController.delete);


module.exports = router;
