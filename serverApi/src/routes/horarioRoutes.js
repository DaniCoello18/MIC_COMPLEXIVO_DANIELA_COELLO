const express = require('express');
const router = express.Router();
const horarioController = require('../controllers/horarioController');
const horarioValidator = require('../validators/horarioValidator');

router.get('/search', horarioValidator.validateSearch, horarioController.search);
router.get('/', horarioController.getAll);
router.get('/:id', horarioValidator.validateId, horarioController.getById);
router.post('/', horarioValidator.validateCreate, horarioController.create);
router.put('/:id', horarioValidator.validateId, horarioValidator.validateUpdate, horarioController.update);
router.delete('/:id', horarioValidator.validateId, horarioController.delete);

module.exports = router;
