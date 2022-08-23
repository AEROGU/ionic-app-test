# Prueba programador ionic + php API REST

## Descripción de la APP

Se requiere una apk que capture la información de una persona y las coordenadas de la misma

1. Crear un apk en ionic que conecte con la API Rest de php para guardar la información 

- Nombre

- Teléfono 
- Email 
- Latitud y longitud 

2. Generar los end points de la Api Rest para la consultas siguientes

- Todos los usuarios

- Información de un usuario por id
- Información de un usuario por teléfono o email

La apk sin firmar solo generarla  para prueba.



## Comenzando 🚀

_Estas instrucciones te permitirán obtener una copia del proyecto en funcionamiento en tu máquina local_

### Pre-requisitos 📋

- PHP 8.0.2 o superior
- NPM (Empaquetado con NodeJS)

Si usa Windows, tener php en variables de entorno.

### Instalación  del servidor en Windows🔧

1. _Una vez clonado el proyecto de GitHub deberás ir a la carpeta "REST-API" y abrir ahí una instancia de PowerShell para instalar las dependencias con Composer_

```bash
composer install
```

2. _Crear un nuevo archivo .env_

```bash
cp .env.example .env
```

3. _Generar una nueva llave dentro del nuevo .env_

```bash
php artisan key:generate
```

4. _Abre el archivo .env y configura la conexión MySQL_ (usuario y contraseña)

5. _Crea la base de datos con tus datos de conección configurados_

```bash
php artisan db:create
```

6. _Ejecuta las migraciónes_

```bash
php artisan migrate
```

7. _Ejecutamos los seeders_

```bash
php artisan db:seed
```

8.  _Ejecutar el servidor PHP_

```bash
php artisan serve
```

Con esto el servidor REST ya se está ejecutando.



### Despliegue de la app en Windows🔧

Abre otra consola en `./APP/registrar-personas/`y ejecuta cada línea.

Asumiendo que Ionic ya está instalado globalmente con npm:

```
npm install
ionic serve
```

La app desde el navegador utilizarla la IP de retorno 127.0.0.1.

Para utilizar la "apk" de android, debes obtener la IP local del servidor REST, y colocarla en el archivo de configuración `./APP/registrar-personas/src/ServerConfig.ts` cambiar `const baseUrl = "127.0.0.1";` por la ip donde se ejecuta el servidor REST. Luego aplicar los cambios a la app Android con:

```bash
ionic build
ionic cap copy
ionic cap open android
```



_Menciona las herramientas que utilizaste para crear tu proyecto_

* [Laravel - The PHP Framework For Web Artisans](https://laravel.com/) - El framework web usado

* [Composer](https://getcomposer.org/) - El gestor de paquetes para PHP

* [npm](https://www.npmjs.com/) - El gestor de paquetes para JavaScript, se instala con [Node.js](https://nodejs.org/es/)

* [Ionic](https://ionicframework.com/docs/) - El UI Toolkit

  

## Autor ✒️

* **Arturo Enrique Rosas Gutiérrez** - [AEROGU](https://github.com/AEROGU)

