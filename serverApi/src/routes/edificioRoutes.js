const express = require('express');
const router = express.Router();
const edificioController = require('../controllers/edificioController');
const edificioValidator = require('../validators/edificioValidator');

router.get('/search', edificioValidator.validateSearch, edificioController.search);
router.get('/', edificioController.getAll);
router.get('/:id', edificioValidator.validateId, edificioController.getById);
router.post('/', edificioValidator.validateCreate, edificioController.create);
router.put('/:id', edificioValidator.validateId, edificioValidator.validateUpdate, edificioController.update);
router.delete('/:id', edificioValidator.validateId, edificioController.delete);

module.exports = router;
