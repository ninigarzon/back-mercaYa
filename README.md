## 1. Se debe instalar las dependencias
Comando: composer i

## 2. Se debe hacer la copia del env.example
cp .env.example .env

## 3. Se debe generar una key
Comando: php artisan key:generate


## 4. Modificacion del env
Para este paso es necesario instalar xampp, cualquier version lo importante es que deben crear una Base de Datos con el nombre que deseen, con el fin de cambiar estas variables en el env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={{Nombre de la Base de Datos que se cree en xampp}}
DB_USERNAME=root
DB_PASSWORD=

## 5. Se correrar migracion
Las migraciones se corren con el fin de crear las tablas que estan en el archivo migration, tambien este borra todos los datos de la Base de Datos, entonces solo hacerlo al inicio o cuando quieran borrar los datos 

Comando: php artisan migrate 

## 6. Se debe correr el proyecto
Comando: php artisan serve

Este comando les dara un url, normalmente es http://127.0.0.1:8000, si es diferente se necerio que vayan al archivo llamado [urlRequest] del frontend, debe remplazarla por la que le sale
