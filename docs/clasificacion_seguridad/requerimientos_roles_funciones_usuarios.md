# Clasificacion de Requerimientos: Roles, Funciones y Usuarios

Este archivo documenta los componentes existentes y actualizados en el proyecto para estos requerimientos:

1. Asignar o inactivar roles a un usuario.
2. Asignar o inactivar funciones a un rol.

## 1) Vistas, controladores y modelos de datos relacionados

### Vistas
- src/Views/templates/usuarios/usuario.view.tpl
- src/Views/templates/usuarios/usuarios.view.tpl
- src/Views/templates/roles/rol.view.tpl
- src/Views/templates/roles/roles.view.tpl
- src/Views/templates/funciones/funcion.view.tpl
- src/Views/templates/funciones/funciones.view.tpl

### Controladores
- src/Controllers/Usuarios/Usuario.php
- src/Controllers/Usuarios/Usuarios.php
- src/Controllers/Roles/Rol.php
- src/Controllers/Roles/Roles.php
- src/Controllers/Funciones/Funcion.php
- src/Controllers/Funciones/Funciones.php

### Modelos de datos (DAO)
- src/Dao/Usuarios/Usuarios.php
- src/Dao/Roles/Roles.php
- src/Dao/Funciones/Funciones.php
- src/Dao/Security/Security.php

### Estructura de tablas (SQL base)
- docs/scripts/01_security.sql

## 2) Funciones en la tabla funciones

```sql
SELECT fncod, fndsc, fnest, fntyp
FROM funciones
ORDER BY fncod;
```

## 3) Funciones relacionadas a los roles

```sql
SELECT fr.rolescod, r.rolesdsc, fr.fncod, f.fndsc, fr.fnrolest, fr.fnexp
FROM funciones_roles fr
INNER JOIN roles r ON r.rolescod = fr.rolescod
INNER JOIN funciones f ON f.fncod = fr.fncod
ORDER BY fr.rolescod, fr.fncod;
```

## 4) Roles registrados

```sql
SELECT rolescod, rolesdsc, rolesest
FROM roles
ORDER BY rolescod;
```

## 5) Usuarios relacionados a los roles

```sql
SELECT ru.usercod, u.username, u.useremail, ru.rolescod, r.rolesdsc, ru.roleuserest, ru.roleuserfch, ru.roleuserexp
FROM roles_usuarios ru
INNER JOIN usuario u ON u.usercod = ru.usercod
INNER JOIN roles r ON r.rolescod = ru.rolescod
ORDER BY ru.usercod, ru.rolescod;
```

## 6) Usuarios

```sql
SELECT usercod, useremail, username, userest, usertipo
FROM usuario
ORDER BY usercod;
```

## Tablas involucradas directamente
- usuario
- roles
- funciones
- roles_usuarios
- funciones_roles
