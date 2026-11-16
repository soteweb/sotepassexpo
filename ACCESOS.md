# SotePass Expo — Guía de Accesos, Cuentas y Rutas del Sistema

Documento de referencia rápida con las credenciales de acceso, configuración de base de datos, rutas principales y detalles de los planes SaaS B2B.

---

## 1. Conexión a Base de Datos (PostgreSQL)

| Parámetro | Valor |
| :--- | :--- |
| **Motor** | PostgreSQL (v14+) |
| **Host** | `127.0.0.1` |
| **Puerto** | `5432` |
| **Base de Datos** | `sotepassexpo` |
| **Usuario** | `postgres` |
| **Contraseña** | `master` |

---

## 2. Cuentas de Acceso y Roles

### A. SuperAdmin (Soteweb)
Acceso total a la administración global de expositores, supervisión de métricas de todos los stands y configuración de planes.
- **URL de Login:** `/login` o directo en `/admin`
- **Correo Principal:** `developer@sotepass.com`
- **Contraseña:** `S0tel0.1202`
- **Correo Alternativo:** `admin@soteweb.com` (pass: `admin123`)
- **Rol:** `admin`

---

### B. Cuentas Demo de Expositores (Empresas Clientes)
Cuentas con acceso multitenant aislado a su propio stand y base de datos de leads.

#### 1. Kurosu & Cía. S.A. (John Deere)
- **Correo:** `kurosu@demo.com`
- **Contraseña:** `demo123`
- **Rol:** `expositor`
- **Plan Asignado:** Stand Corporativo VIP 4K
- **PIN de Mostrador:** `1234`
- **Panel CRM:** `/panel/kurosu`

#### 2. Somax Agro S.A.
- **Correo:** `somax@demo.com`
- **Contraseña:** `demo123`
- **Rol:** `expositor`
- **Plan Asignado:** Stand Pro Ruleta Touch
- **PIN de Mostrador:** `1234`
- **Panel CRM:** `/panel/somax`

---

## 3. Mapa de Rutas de la Plataforma

### Públicas e Informativas (Sin barra lateral)
- **Landing de Presentación (B2B):** `/`  
  *Presentación visual limpia y persuasiva para directivos de empresas expositoras.*
- **Inicio de Sesión:** `/login`
- **Registro de Nueva Empresa:** `/register`

### Experiencias de Stand para Visitantes (Públicas / Pantalla Completa)
- **Registro Móvil del Visitante (QR en mostrador):** `/stand/{slug}`  
  *(Ejemplo: `/stand/kurosu`, `/stand/somax`)*
- **Ruleta Digital de Premios (Touch / Tótem):** `/ruleta/{slug}/{leadId}`  
  *(Se abre automáticamente al registrarse el visitante o desde el mostrador)*
- **Sorteo en Pantalla Gigante TV 4K:** `/sorteo/{slug}`  
  *(Ejemplo: `/sorteo/kurosu` — Pantalla en vivo con tambor digital, sonidos y confeti)*

### Gestión Protegida (Requiere Autenticación)
- **Redirección Inteligente (Dashboard):** `/dashboard`
- **Onboarding / Alta de Stand:** `/stands/create`  
  *Wizard paso a paso donde el expositor elige su plan y configura su stand.*
- **Panel CRM del Stand:** `/panel/{slug}`  
  *Métricas en vivo, editor de stock de premios, validador de cupones y tabla de leads.*
- **Exportación de Contactos a Excel (CSV con UTF-8 BOM):** `/export/{slug}`
- **Panel SuperAdmin Soteweb:** `/admin`  
  *Supervisión global de empresas, métricas consolidadas y creación rápida de stands.*

---

## 4. Planes SaaS B2B Disponibles

| Plan | Inversión | Características Principales |
| :--- | :--- | :--- |
| **Stand Express QR** | Gs. 1.200.000 | Landing con marca propia, captura de WhatsApp/Ciudad/Perfil/Hectáreas, base de datos en tiempo real y descarga Excel. |
| **Stand Pro Ruleta Touch** *(Más Popular)* | Gs. 2.500.000 | Todo lo del plan Express + Ruleta interactiva táctil, control de stock de premios, cupones antifraude y política de giros diaria. |
| **Stand Corporativo VIP 4K** *(Empresas Líderes)* | Gs. 4.500.000 | Todo lo del plan Pro + Pantalla gigante para sorteos en TV 4K en vivo, tambor digital con sonido de estadio y soporte técnico prioritario. |

---

## 5. Comandos Útiles

- **Iniciar Servidor Local:**
  ```powershell
  php artisan serve
  ```
- **Ejecutar Pruebas Automatizadas:**
  ```powershell
  php artisan test
  ```
- **Volver a Cargar Datos Demo:**
  ```powershell
  php artisan migrate:fresh --seed
  ```
- **Revisar y Formatear Código (Pint):**
  ```powershell
  vendor\bin\pint app resources routes tests --format agent
  ```

