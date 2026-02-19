const express = require('express');
const cors = require('cors');
require('dotenv').config();

const alumnoRoutes = require('./routes/alumnoRoutes');
const especialidadRoutes = require('./routes/especialidadRoutes');
const profesorRoutes = require('./routes/profesorRoutes');
const edificioRoutes = require('./routes/edificioRoutes');
const materiaRoutes = require('./routes/materiaRoutes');
const horarioRoutes = require('./routes/horarioRoutes');
const matriculaRoutes = require('./routes/matriculaRoutes');
const inventarioRoutes = require('./routes/inventarioRoutes');
const inventario1Routes = require('./routes/inventario1Routes');

const app = express();

// Middlewares
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Rutas
app.use('/alumnos', alumnoRoutes);
app.use('/especialidades', especialidadRoutes);
app.use('/profesores', profesorRoutes);
app.use('/edificios', edificioRoutes);
app.use('/materias', materiaRoutes);
app.use('/horarios', horarioRoutes);
app.use('/matriculas', matriculaRoutes);
app.use('/inventarios', inventarioRoutes);
app.use('/inventarios1', inventario1Routes);



// Ruta base opcional
app.get('/', (req, res) => {
    res.json({ message: 'Microservicio funcional' });
});

module.exports = app;
