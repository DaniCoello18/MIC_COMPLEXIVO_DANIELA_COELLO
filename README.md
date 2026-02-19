# MIC_COMPLEXIVO_DANIELA_COELLO

Este repositorio contiene dos proyectos:

1. **GestionUni** - Aplicación Laravel (PHP) ubicada en `GestionUni/`.
2. **serverApi** - API Node.js/Express ubicada en `serverApi/`.

## Configuración después de clonar

### 1. Proyecto Laravel (GestionUni)

```bash
cd GestionUni
cp .env.example .env          # o crea tu propio .env
composer install              # instalar dependencias
php artisan key:generate      # genera la clave de aplicación
php artisan migrate           # ejecutar migraciones
npm install && npm run dev    # frontend con Vite (opcional)
```

Asegúrate de tener configurada una base de datos en `.env`.

### 2. API Node.js (serverApi)

```bash
cd serverApi
cp .env.example .env          # ajustar variables de entorno
npm install                   # instalar paquetes
npm run start                 # iniciar servidor (o npm run dev si usas nodemon)
```

### Repositorio Git

Este repositorio ya incluye un `.gitignore` global que cubre ambos proyectos. Si deseas excluir otros archivos, modifícalo.

---

¡Listo! Con estos pasos podrás levantar ambos servicios en tu entorno local.
