# Intelligent-Employee-Management-System

A dynamic web-based employee management system with server-side DataTable processing, live search, paginated browsing, smart form validation, and full CRUD operations.

---

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [Validation Rules](#validation-rules)
- [Screenshots](#screenshots)
- [Credits](#credits)

---

## Overview

Intelligent Employee Management System is a PHP-based web application that allows administrators to manage employee records efficiently. It features a server-side DataTable with advanced controls including live search, customizable entries display, pagination with Previous/Next navigation, and complete CRUD (Create, Read, Update, Delete) functionality with smart input validation.

---

## Features

### Core CRUD Operations
- **Create** – Add new employees via a modal form with real-time validation
- **Read** – View all employees in a dynamic server-side DataTable
- **Update** – Edit employee details inline with pre-filled form fields
- **Delete** – Remove individual employees with confirmation prompt
- **Delete All** – Bulk delete all records with double confirmation safety

### Advanced DataTable Controls
- **Live Search** – Filter records instantly across all columns (Name, Email, Mobile, City)
- **Show Entries** – Customize how many records display per page (10, 25, 50, 100)
- **Previous/Next Navigation** – Browse through pages with icon-enhanced pagination buttons
- **Column Sorting** – Sort records by clicking column headers
- **Server-Side Processing** – Efficient data loading for large datasets

### Smart Form Validation
- **Name Validation** – Requires at least 2 names, each starting with a capital letter (e.g., John Smith)
- **Email Validation** – Ensures proper email format
- **Mobile Validation** – Requires minimum 10 digits
- **City Validation** – Ensures city field is not empty
- **Real-Time Error Display** – Red error messages with icons appear below invalid fields
- **Visual Feedback** – Input borders turn green (valid) or red (invalid)

### User Interface Enhancements
- Font Awesome icons throughout (buttons, labels, inputs, pagination)
- Clean, modern design with responsive layout
- Toast notifications for success/error feedback
- Modal forms with Close button beside Submit
- Smooth hover effects and transitions
- No horizontal scrollbar on the table

---

## Technologies Used

| Technology | Purpose |
|------------|---------|
| **PHP** | Server-side scripting and database operations |
| **MySQL** | Database management |
| **jQuery** | AJAX requests and DOM manipulation |
| **DataTables** | Server-side table with search, pagination, sorting |
| **Bootstrap 5** | Responsive layout and modal components |
| **Font Awesome 6** | Icons for buttons, labels, inputs, and pagination |
| **HTML5/CSS3** | Structure and styling |

---

## Project Structure
