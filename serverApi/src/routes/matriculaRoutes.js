const express = require('express');
const router = express.Router();
const matriculaController = require('../controllers/matriculaController');
const matriculaValidator = require('../validators/matriculaValidator');

router.get('/search', matriculaValidator.validateSearch, matriculaController.search);
router.get('/', matriculaController.getAll);
router.get('/:id', matriculaValidator.validateId, matriculaController.getById);
router.post('/', matriculaValidator.validateCreate, matriculaController.create);
router.put('/:id', matriculaValidator.validateId, matriculaValidator.validateUpdate, matriculaController.update);
router.delete('/:id', matriculaValidator.validateId, matriculaController.delete);

module.exports = router;
