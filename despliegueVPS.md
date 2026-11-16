# Guía Maestra de Despliegue en VPS (aaPanel + Nginx + PHP 8.3 + PostgreSQL)

Documento técnico paso a paso para desplegar aplicaciones Laravel (TALL Stack) en el mismo servidor VPS utilizando aaPanel y compartiendo la instancia central de PostgreSQL (puerto 5432) de forma 100% aislada.

---

## 1. Especificaciones del Entorno
- **Panel de Control:** aaPanel (Linux)
- **Servidor Web:** Nginx (Usuario del sistema: `www:www`)
- **Versión PHP:** PHP 8.3 (Extensiones obligatorias: `pdo_pgsql`, `pgsql`, `fileinfo`, `opcache`)
- **Base de Datos:** PostgreSQL en `127.0.0.1:5432` (Multibase de datos)
- **Directorio de Sitios:** `/www/wwwroot/<dominio_o_subdominio>`
- **Directorio Raíz Web:** `/www/wwwroot/<dominio_o_subdominio>/public`

---

## 2. Paso a Paso para un Nuevo Proyecto

### Paso 1: Configurar DNS
En el gestor de DNS de tu dominio (Cloudflare o registrador):
- **Tipo:** `A`
- **Nombre:** `expo` (o el subdominio elegido)
- **Contenido (IPv4):** `IP_PUBLICA_DE_TU_VPS`
- **Proxy:** Desactivado (DNS Only / Nube gris) durante la emisión inicial del SSL.

---

### Paso 2: Crear el Sitio en aaPanel
1. Ve a **Website** &rarr; **Add site**:
   - **Domain:** `expo.sotepass.com`
   - **Database:** `Do not create` *(se gestiona en PostgreSQL)*
   - **PHP Version:** `PHP-83`
2. Configuración obligatoria dentro del sitio (**Site Settings**):
   - **Site directory:**
     - *Running directory:* cambiar a **`/public`** &rarr; Guardar.
   - **URL rewrite:**
     - Seleccionar la plantilla **`laravel5`** &rarr; Guardar:
       ```nginx
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       ```
   - **SSL:**
     - Pestaña **Let's Encrypt** &rarr; Marcar el dominio &rarr; **Apply** (Activar forzado HTTPS).

---

### Paso 3: Clonar el Repositorio de GitHub
Conéctate por SSH al VPS y ejecuta:

```bash
# Entrar al directorio creado por aaPanel
cd /www/wwwroot/expo.sotepass.com

# Eliminar archivos plantilla de aaPanel
rm -rf * .user.ini

# Clonar el proyecto directamente en la raíz del sitio
git clone https://github.com/soteweb/sotepassexpo.git .
```

---

### Paso 4: Crear la Base de Datos en PostgreSQL (Puerto 5432)
Para convivir con otros proyectos en el mismo puerto 5432 sin conflictos, nos conectamos vía TCP:

```bash
# 1. Crear el usuario con su contraseña
psql -h 127.0.0.1 -p 5432 -U postgres -c "CREATE USER sotepassexpo WITH PASSWORD 'TuPasswordSeguro123';"

# 2. Crear la base de datos asignando el dueño
psql -h 127.0.0.1 -p 5432 -U postgres -c "CREATE DATABASE sotepassexpo OWNER sotepassexpo;"

# 3. Otorgar privilegios sobre la base y el esquema público
psql -h 127.0.0.1 -p 5432 -U postgres -c "GRANT ALL PRIVILEGES ON DATABASE sotepassexpo TO sotepassexpo;"
psql -h 127.0.0.1 -p 5432 -U postgres -d sotepassexpo -c "GRANT ALL ON SCHEMA public TO sotepassexpo;"
```
*(Si solicita clave, ingresa la del usuario `postgres` del VPS).*

---

### Paso 5: Configurar el Archivo `.env`
```bash
cp .env.example .env
nano .env
```

Ajustar los siguientes parámetros:
```dotenv
APP_NAME="SotePass Expo"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://expo.sotepass.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=sotepassexpo
DB_USERNAME=sotepassexpo
DB_PASSWORD=TuPasswordSeguro123
```
*(Guardar con `Ctrl + O`, `Enter` y salir con `Ctrl + X`).*

---

### Paso 6: Secuencia de Instalación (Comando Maestro)
Copia y pega este bloque completo en la terminal:

```bash
# 1. Instalar dependencias de producción
composer install --no-dev --optimize-autoloader

# 2. Generar clave criptográfica
php artisan key:generate --force

# 3. Limpiar configuraciones previas
php artisan config:clear

# 4. Migrar tablas y cargar planes/datos iniciales
php artisan migrate:fresh --seed --force

# 5. Enlace simbólico de storage
php artisan storage:link

# 6. Permisos de aaPanel (Crucial para evitar Error 500)
chown -R www:www /www/wwwroot/expo.sotepass.com
chmod -R 775 storage bootstrap/cache

# 7. Cachear para máximo rendimiento
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Paso 7: Crear el Usuario SuperAdmin
Ejecutar mediante Tinker en una sola línea:

```bash
php artisan tinker --execute 'App\Models\User::updateOrCreate(["email" => "developer@sotepass.com"], ["name" => "Developer Soteweb", "company_name" => "Soteweb", "role" => "admin", "password" => Hash::make("S0tel0.1202")]);'
```

---

## 3. Script para Despliegues Futuros (Actualización Rápida)
Cada vez que subas cambios a GitHub (`git push origin main`), entra a tu VPS y ejecuta este bloque para actualizar la web en segundos:

```bash
cd /www/wwwroot/expo.sotepass.com
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www:www /www/wwwroot/expo.sotepass.com
```

---

## 4. Tarea Programada en aaPanel (Cron)
Para que los sorteos, expiraciones y colas funcionen automáticamente:
- **aaPanel &rarr; Cron &rarr; Add task:**
  - **Type:** Shell Script
  - **Name:** SotePass Expo Scheduler
  - **Period:** Every 1 Minute
  - **Script:**
    ```bash
    /www/server/php/83/bin/php /www/wwwroot/expo.sotepass.com/artisan schedule:run >> /dev/null 2>&1
    ```

---

## 5. Solución a Problemas Comunes

| Error | Causa | Solución |
| :--- | :--- | :--- |
| **`file_get_contents(.env): Failed to open stream`** | No existe el archivo `.env`. | Ejecutar `cp .env.example .env` antes de `key:generate`. |
| **`FATAL: role "x" does not exist`** | El usuario no existe en el PostgreSQL del puerto 5432. | Crear el usuario conectándose con `-h 127.0.0.1 -p 5432`. |
| **`Error 500` tras desplegar** | Permisos incorrectos en carpetas de escritura. | Ejecutar `chown -R www:www /www/wwwroot/dominio` y `chmod -R 775 storage bootstrap/cache`. |
| **Los cambios de `.env` no surten efecto** | La configuración está cacheada en memoria. | Ejecutar `php artisan config:clear` y luego `php artisan config:cache`. |
