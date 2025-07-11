
# Mascotas-api

## Instrucciones para instalar y correr el proyecto

1. Clonar el repositorio:
```
git clone https://github.com/Josoxd01/Mascotas-api.git
cd Mascotas-api
```

2. Instalar dependencias:
```
composer install
```

3. Copiar el archivo de entorno y generar las claves necesarias:
```
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Tener instalado XAMPP y activar los módulos de Apache y MySQL.

5. Crear una base de datos llamada `mascotas_db` y configurar las credenciales en el archivo `.env`.

6. Ejecutar las migraciones:
```
php artisan migrate
```

7. Levantar el servidor de desarrollo:
```
php artisan serve
```

---

## Documentación de Endpoints

### Autenticación (no requiere token)

**POST /api/register** - Registrar nuevo usuario  
**Body:**
```json
{
  "name": "Jose Mora",
  "email": "jose@mail.com",
  "password": "123456",
  "password_confirmation": "123456"
}
```

**POST /api/login** - Iniciar sesión y obtener token  
**Body:**
```json
{
  "email": "jose@mail.com",
  "password": "123456"
}
```

**GET /api/me** - Obtener datos del usuario logueado (requiere token)

---

## CRUD de Personas (requiere token)

**GET /api/personas** - Listar todas las personas

**POST /api/personas** - Crear nueva persona  
**Body:**
```json
{
  "nombre": "Jose Mora",
  "email": "josemorinia@gmial.com",
  "fecha_nacimiento": "2000-08-05"
}
```

**GET /api/personas/{id}** - Obtener persona por ID  
(Opcional: `?incluyremascota=si` para incluir mascotas)

**PUT /api/personas/{id}** - Actualizar persona  
**Body:**
```json
{
  "nombre": "Jose Mora",
  "email": "josemorinia@gmial.com",
  "fecha_nacimiento": "2000-08-05"
}
```

**DELETE /api/personas/{id}** - Eliminar persona (soft delete)

---

## CRUD de Mascotas (requiere token)

**GET /api/mascotas** - Listar todas las mascotas

**POST /api/mascotas** - Crear nueva mascota (integra con TheDogAPI o TheCatAPI)  
**Body:**
```json
{
  "nombre": "Firulais",
  "especie": "Perro",
  "raza": "Husky",
  "edad": 3,
  "persona_id": 1
}
```

**GET /api/mascotas/{id}** - Obtener mascota por ID

**PUT /api/mascotas/{id}** - Actualizar una mascota  
**Body:**
```json
{
  "nombre": "Firulais",
  "especie": "Perro",
  "raza": "Husky",
  "edad": 3,
  "persona_id": 1
}
```

**DELETE /api/mascotas/{id}** - Eliminar una mascota

---

## Usuario de prueba

```
Email: jose@mail.com
Password: 123456
```

---

## Consideraciones importantes del desarrollador

- Las rutas están protegidas con JWT y requieren token de autenticación excepto `register` y `login`.
- Se integró con TheDogAPI y TheCatAPI para obtener imágenes al crear una mascota.
- Se incluyeron validaciones y manejo de errores personalizados en cada controlador.
- Se puede usar `?incluyremascota=si` para traer las mascotas al consultar una persona.
- El sistema permite rollback y transacciones seguras para operaciones de creación y actualización.
- Se utilizó Eloquent ORM y migraciones para el manejo de la base de datos.

