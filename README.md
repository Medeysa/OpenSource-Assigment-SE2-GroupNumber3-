# Software Project Tracking System

## Team Information

**Degree Program:** Software Engineering

**Group Number:** 03

## Project Description

The Software Project Tracking System is a PHP and MySQL web application for Software Engineering students. The system records software project information, displays project status, and searches projects by project name.

## Overview

This project helps a software development team keep track of project records in one place. Authenticated users can add project details, view project progress status, and search for projects using the project name.

## Current Phase

Phase 7: Documentation update.

## Features

- User authentication and session management
- Record software project information
- Display project status
- Search projects by project name
- Responsive frontend design using CSS
- JavaScript mobile navigation and date validation
- Project update feature planned on a development branch

## Installation

1. Install XAMPP.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Copy the project folder into `C:\xampp\htdocs`.
4. Open phpMyAdmin at `http://localhost/phpmyadmin`.
5. Import `database.sql`.
6. Add a user account to the `users` table using a hashed password.
7. Open the project in the browser using `http://localhost/OpenSource-Assigment-SE2-GroupNumber3-/`.

## Git Commands Used

```bash
git add .
git commit -m "Phase 1: Added project folders, homepage, README update, and gitignore"
git commit -m "Phase 2: Added MySQL database schema and PDO connection configuration"
git commit -m "Phase 3: Added login, logout, password verification, and session helper files"
git commit -m "Phase 4: Added protected project registration form, validation, duplicate check, and database insert"
git commit -m "Phase 5: Added protected project records table with database fetch and empty-state display"
git commit -m "Phase 6: Added protected project search with prepared query and result display"
git commit -m "Design Update: Added responsive shared layout, professional styling, mobile navigation, and JavaScript interactions"
git commit -m "Correction"
git commit -m "Phase 7: Updated README documentation and added project date validation notes"
```

## Repository Link

Repository link: `https://github.com/Medeysa/OpenSource-Assigment-SE2-GroupNumber3-.git`

## Technologies Used

- PHP
- MySQL
- HTML
- CSS
- JavaScript
- Git
- GitHub

## Folder Structure

```text
config/
assets/
  css/
  js/
includes/
auth/
projects/
```

