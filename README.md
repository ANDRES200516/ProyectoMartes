# Proyecto Plataforma de Cursos Online

# Descripción del sistema

Este proyecto es una plataforma web desarrollada en PHP bajo arquitectura MVC para la gestión de cursos online. El sistema permite administrar usuarios, cursos, módulos, lecciones, certificados y procesos académicos desde un panel administrativo.

La plataforma incluye funcionalidades como autenticación de usuarios, inscripción a cursos, seguimiento del progreso, sistema de notificaciones y gestión completa de contenido educativo.

---

# Tecnologías utilizadas

* PHP
* MySQL
* HTML5
* CSS3
* JavaScript
* Arquitectura MVC
* Variables de entorno `.env`
* Git y GitHub

---

# Estructura del proyecto

```bash
Proyecto/
│
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   └── Views/
│
├── public/
│   └── index.php
│
├── config/
├── database.sql
├── .env
├── .gitignore
└── README.md
```

---

# Arquitectura MVC

## Modelo (Model)

Gestiona la lógica del sistema y la interacción con la base de datos.

## Vista (View)

Contiene las interfaces gráficas y formularios del sistema.

## Controlador (Controller)

Procesa solicitudes, valida datos y conecta modelos con vistas.

---

# Funcionalidades

* Registro e inicio de sesión
* Gestión de usuarios
* Administración de cursos
* Gestión de módulos y lecciones
* Inscripción de estudiantes
* Sistema de notificaciones
* Certificados
* Panel administrativo

---

# Seguridad

El sistema implementa medidas básicas de seguridad como:

* Manejo de sesiones
* Validación de usuarios
* Uso de variables de entorno `.env`
* Separación de responsabilidades con MVC
* Organización segura de archivos y configuraciones
