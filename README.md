# MedioLike Backend

Gestión de seguimiento de la comunidad de MedioLike.

**Autora:** Marijose Vinajera, estudiante de ingeniería de software (UTM Mérida).

## De qué se trata

MedioLike Backend es la API que concentra el seguimiento de la comunidad de MedioLike: el catálogo de capacitaciones, los grupos con fecha de inicio y cierre, la inscripción de cada persona y lo que ocurre después (pago, beca, acciones de acompañamiento y constancia). Sirve para tener ese recorrido en un solo modelo, consultable por API, para quien coordina la comunidad.

## Qué hace hoy

**Publicado en rutas**

- CRUD de capacitaciones (`trainings`): listar, ver una, crear, actualizar y eliminar.
- Búsqueda de capacitaciones por nombre (`LIKE` sobre `name`).
- Consulta del usuario autenticado en `GET /api/user`.
- Página de bienvenida de Laravel en `/` y comprobación de salud en `/up`.
- Las rutas de `routes/api.php` pasan por el middleware `auth:sanctum`.

**Escrito en modelos y controladores, sin ruta registrada**

Hay controladores con `index`, `show`, `store`, `update` y `destroy`, y validación de entrada, para usuarios, grupos de capacitación, inscripciones, descuentos, becas, pagos, acciones de seguimiento y constancias. Esos controladores viven en `app/Http/Controllers` y no están registrados en `routes/api.php` ni en `routes/web.php`.

## Stack

| Pieza | En este repositorio |
| --- | --- |
| PHP | `^8.2` (`composer.json`) |
| Laravel | 12 (`laravel/framework` `^12.0`; 12.43.1 en `composer.lock`) |
| Base de datos | MariaDB en `.env.example` (`DB_CONNECTION=mariadb`, base `api_rest`, puerto 3306). Sin `.env`, `config/database.php` usa SQLite. PHPUnit usa SQLite en memoria. |
| Autenticación | Laravel Sanctum 4 (`^4.0`; 4.3.0 en el lock), middleware `auth:sanctum` y migración `personal_access_tokens`. El guard por defecto es `web` (sesión). `User` no usa el trait `HasApiTokens`. No hay rutas de login ni de registro. |
| ORM | Eloquent |
| Pruebas | PHPUnit 11 (11.5.46 en el lock) |
| Página de inicio | Vite 7, Tailwind CSS 4 y Axios, dependencias de desarrollo del esqueleto de Laravel. `routes/web.php` solo devuelve `welcome`. |
| Otras | `laravel/tinker`. Desarrollo: Pint, Sail, Pail, Faker, Collision, Mockery y Laravel Boost. |

## Endpoints

Laravel antepone el prefijo `/api` a `routes/api.php`. Las filas `/api/*` exigen `auth:sanctum`.

| Método | Ruta | Qué hace |
| --- | --- | --- |
| `GET` | `/` | Vista `welcome` (pública) |
| `GET` | `/up` | Health check de Laravel (pública), registrada en `bootstrap/app.php` |
| `GET` | `/api/trainings` | Lista las capacitaciones. JSON `200` con `data`, `status` y `message` |
| `POST` | `/api/trainings` | Crea una. `name` obligatorio (string, máx. 255); `description` opcional. Responde `201` |
| `GET` | `/api/trainings/{id}` | Una capacitación por id |
| `PUT` | `/api/trainings/{id}` | Actualiza `name` y/o `description` (`sometimes`). `400` si el cuerpo válido llega vacío; `404` si el id no existe |
| `DELETE` | `/api/trainings/{id}` | Elimina la capacitación y devuelve el registro borrado |
| `GET` | `/api/trainings/search/{name}` | Capacitación cuyo `name` contiene `{name}` |
| `GET` | `/api/user` | Devuelve el usuario de la petición (`$request->user()`) |

`GET /api/trainings/search/{name}` está declarada después de `GET /api/trainings/{id}`. Laravel usa la primera ruta que coincide, así que `/api/trainings/search/laravel` entra en `show` con `id` igual a `search`.

En `index`, `show`, `store`, `destroy` y `search`, un registro inexistente o un fallo de validación cae en el `catch` general y responde `500`. `update` sí separa el `404`.

## Modelo de datos

`php artisan migrate` crea las tablas del esqueleto y de Sanctum: `users`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs` y `personal_access_tokens`. Las tablas del dominio están descritas en los modelos y todavía no tienen migración.

| Modelo | Tabla | Campos asignables | Relación |
| --- | --- | --- | --- |
| `User` | `users` | `name`, `email`, `password` | `Authenticatable`. Cast `hashed` en `password`. Oculta `password` y `remember_token`. |
| `Training` | `trainings` | `name`, `description`, `created_at`, `updated_at` | — |
| `TrainingGroup` | `training_groups` | `training_id`, `group_name`, `start_date`, `end_date`, timestamps | `training()`: `hasOne` hacia `Training` |
| `Enrollment` | `enrollments` | `user_id`, `training_group_id`, `enrolled_at`, timestamps | `user()` y `trainingGroup()`: `hasOne` |
| `Discount` | `discounts` | `code`, `discount_type`, `discount_value`, `description`, `is_active`, timestamps | La propiedad de tabla está escrita como `$tables` |
| `Scolarship` | `scolarships` | `enrollment_id`, `discount_id`, `approved_by`, `approved_at`, `notes`, timestamps | `enrollment()` y `discount()`: `hasOne`. Propiedad `$tables` |
| `Payment` | `payments` | `enrollment_id`, `amount`, `payment_method`, `payment_reference`, `receipt_path`, `payment_date`, `validated_at`, `validated_by`, timestamps | `enrollment()`: `hasOne`. Propiedad `$tables` |
| `FollowUpAction` | `follow_up_actions` | `enrollment_id`, `action_type`, `notes`, `performed_by`, `performed_at`, timestamps | `enrollment()`: `belongsTo` `Enrollment` |
| `Certificate` | `certificates` | `enrollment_id`, `certificate_code`, `certificate_path`, `deliverated_at`, timestamps | `enrollment()`: `hasOne`. El controlador valida `delivered_at` |

Reglas que ya están en los controladores (aunque la ruta no esté publicada):

- Descuento: `discount_type` en `percentage` o `amount`.
- Pago: `payment_method` en `transfer`, `cash` o `card`.
- Acción de seguimiento: `action_type` en `registered`, `payment_instructions_sent`, `scholarship_applied`, `payment_received`, `payment_validated`, `welcome_sent`, `training_started`, `training_finished`, `certificate_delivered`.
- Grupo, inscripción, beca, pago y constancia comprueban llaves con `exists` sobre `trainings`, `users`, `training_groups`, `enrollments` o `discounts`.

`DatabaseSeeder` crea el usuario `test@example.com` con `UserFactory`. `UserTableSeeder` define `admin@example.com` y no se invoca desde `DatabaseSeeder`.

## Cómo levantarlo

Hace falta PHP 8.2 o superior, Composer y un MariaDB (o MySQL compatible) con la base `api_rest`, el nombre que trae `.env.example`.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

La aplicación queda en `http://localhost:8000`. `migrate` crea las tablas de Laravel y Sanctum. Las tablas de capacitaciones, inscripciones y el resto del dominio no salen de las migraciones actuales.

Usuario de prueba del seeder principal:

```bash
php artisan db:seed
```

La vista `/` incluye su propio CSS. Para compilar los assets con Vite: `npm install` y `npm run dev`.

## Pruebas

`phpunit.xml` fija `DB_CONNECTION=sqlite` y `DB_DATABASE=:memory:`, así que las pruebas no usan MariaDB.

```bash
php artisan test
```

`composer test` limpia la configuración y ejecuta el mismo comando.

Hay dos pruebas de ejemplo del esqueleto. `tests/Feature/ExampleTest.php` comprueba que `GET /` responde 200. `tests/Unit/ExampleTest.php` comprueba que `true` es `true`. No hay pruebas de los controladores del dominio.

## Estructura

```text
app/Http/Controllers/     CRUD por entidad; solo TrainingController está enrutado
app/Models/               User y el dominio de seguimiento
routes/api.php            Rutas publicadas de la API
routes/web.php            GET /
bootstrap/app.php         Carga de rutas y health check /up
database/migrations/      Tablas de Laravel y personal_access_tokens
database/seeders/         DatabaseSeeder y UserTableSeeder
database/factories/       UserFactory
tests/Feature, tests/Unit Pruebas de ejemplo
resources/views/          welcome.blade.php
```

## Lo que aprendí

Modelé el seguimiento de la comunidad como un recorrido corto: la capacitación se ofrece en un grupo, la persona se inscribe y, desde esa inscripción, se anotan el pago, la beca, las acciones de acompañamiento y la constancia. En los controladores practiqué la validación de Laravel (`required`, `sometimes`, `exists` y listas `in`), un sobre JSON común (`data`, `status`, `message`) y el middleware `auth:sanctum` delante de la API.

Licencia MIT, declarada en `composer.json`.
