const express = require('express');
const router = express.Router();
const inventario1Controller = require('../controllers/inventario1Controller');


router.get('/', inventario1Controller.getAll);


module.exports = router;