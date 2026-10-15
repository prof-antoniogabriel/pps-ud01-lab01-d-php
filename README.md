# Proyecto D · API de avisos de seguridad (PHP)

Proyecto didáctico para **UD01-LAB01. Radiografía de proyectos**. Una API REST de avisos con PHP y dependencias gestionadas por Composer.

## Reglas del laboratorio

- Trabaja exclusivamente sobre los repositorios facilitados y en tu propio equipo o laboratorio autorizado.
- Para completar el cuestionario Moodle, **identifica los archivos y las líneas de código** que justifican cada respuesta. Este README explica cómo ejecutar la aplicación, pero no sustituye el análisis del código.
- No necesitas modificar el código ni entregar un informe.
- Los datos son de prueba. No publiques esta aplicación en Internet como servicio de producción.

## Preparar y explorar el proyecto

1. Descarga el ZIP del proyecto y extráelo.
2. Abre una terminal **dentro de la carpeta que contiene el archivo principal de dependencias**.
3. Abre la carpeta con VS Code, IntelliJ IDEA u otro editor.
4. Elige **una** de las dos formas de ejecución: local o Docker. No arranques ambas en el mismo puerto simultáneamente.

Los comandos siguientes asumen una terminal de Linux/macOS (o WSL en Windows). `curl` puede ejecutarse en otra terminal mientras el servidor sigue en marcha.

## Requisitos para ejecución local

- **PHP 8.3** con extensiones necesarias para instalar las dependencias.
- **Composer 2** instalado en el sistema.
- Comprueba: `php -v` y `composer --version`.

> Para ejecutar las pruebas con PHPUnit, puede ser necesario tener habilitadas extensiones como `dom`, `mbstring` y `xml`. Compruébalas con `php -m`.

## Opción 1 · Ejecutar con PHP y Composer

Desde la raíz del repositorio:

```bash
composer install
composer start
```

El segundo comando lanza el servidor integrado de PHP en `http://localhost:8080`. Este servidor es adecuado para pruebas y docencia, no para producción. Detén con `Ctrl+C`.

También puedes arrancarlo sin usar el script de Composer:

```bash
php -S localhost:8080 -t public public/router.php
```

## Configuración opcional

Puedes consultar `.env.example` para conocer los parámetros previstos. **No se carga automáticamente un archivo `.env`**: se leen variables del entorno del proceso PHP. Por ejemplo, en Linux/macOS:

```bash
APP_ENV=development composer start
```

En PowerShell:

```powershell
$env:APP_ENV='development'
composer start
```

El puerto está fijado en el comando `composer start`; modificar `APP_PORT` no cambia ese comando. Para utilizar otro puerto, arranca el servidor manualmente con `php -S localhost:8081 -t public public/router.php`.

## Ejecutar pruebas y preparar dependencias

```bash
composer test
composer build
```

El script `build` optimiza el autoload de Composer; **no compila PHP a un binario**. Si habías ejecutado `composer build`, puede que se eliminen dependencias de desarrollo: para volver a lanzar PHPUnit, ejecuta `composer install`.

## Opción 2 · Ejecutar con Docker

```bash
docker build -t lab01-php .
docker run --rm --name lab01-php -p 8080:8080 lab01-php
```

Desde otra terminal, prueba `curl -i http://localhost:8080/health`. Para detener el contenedor: `Ctrl+C` o `docker stop lab01-php`.

## Probar la API

| Método | Ruta | Resultado esperado |
| --- | --- | --- |
| GET | `/health` | Estado de la aplicación |
| GET | `/api/notices` | Listado JSON |
| GET | `/api/notices/1` | Detalle del aviso 1 |
| POST | `/api/notices` | Crea un aviso (HTTP 201) |

```bash
curl -i http://localhost:8080/health
curl -i http://localhost:8080/api/notices
curl -i http://localhost:8080/api/notices/1
curl -i -X POST http://localhost:8080/api/notices \
  -H 'Content-Type: application/json' \
  -d '{"title":"Comprobar accesos","severity":"high"}'
```

Los valores de `severity` válidos son `low`, `medium` y `high`, en minúsculas.

## Datos y almacenamiento

Los avisos se almacenan en un array en memoria dentro del servicio. **Atención:** al usar el servidor integrado PHP, cada petición HTTP crea un ciclo de ejecución nuevo; por tanto, un `POST` puede devolver un aviso creado, pero **no permanece disponible en un `GET` posterior**. Esto es intencionadamente un ejemplo sin persistencia.

## Problemas frecuentes

- **`composer: command not found`**: instala Composer o usa Docker.
- **Extensiones PHP ausentes**: revisa el listado de extensiones requerido por Composer y ejecuta `php -m`.
- **Error `vendor/autoload.php` no encontrado**: ejecuta `composer install`.
- **Puerto 8080 ocupado**: arranca con `php -S localhost:8081 -t public public/router.php` o publica `-p 8081:8080` en Docker.
- **HTTP 400 al crear avisos**: comprueba `title`, `severity` y `Content-Type: application/json`.
