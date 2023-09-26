# Rezerva: Restaurant Table Booking Web Application (PHP)

This is the Backend of **Rezerva**, A Restaurant Table Booking Web Application built using PHP. Rezerva simplifies the process of making restaurant reservations, whether you're dining solo or with a companion. This comprehensive guide will help you understand the app's functionality, setup, and usage.

## Introduction

Rezerva is a feature-rich Restaurant Table Booking Web App that simplifies the process of reserving tables for your customers. It caters to both solo diners and those dining with a companion. This PHP-based web application utilizes a RESTful API to handle reservation management, user authentication, and restaurant details.

## Key Features

- **User-Friendly Booking**: Effortlessly reserve tables for one or two persons.
- **Authentication & Authorization**: Secure user authentication with role-based access (USER, ADMIN, SUPERADMIN).
- **Reservation Management**: Book, update, and cancel reservations conveniently.
- **Restaurant Details**: Access comprehensive restaurant information.
- **Admin & Superadmin Controls**: Manage restaurants and brands with ease.
- **Flexible API**: Secure API endpoints for role-based actions.

## System Requirements

Before you start, ensure your system meets the following requirements:

- **PHP**: PHP must be installed on your server.
- **Database**: Set up a compatible database system (e.g., MySQL) and create the required tables.

## Installation

1. Clone the Rezerva repository to your server:

   ```shell
   git clone https://github.com/your-username/rezerva.git
   ```

2. Navigate to the project directory:

   ```shell
   cd rezerva
   ```

3. Install any necessary PHP packages or dependencies (if needed).

## Configuration

To configure Rezerva for your environment:

1. Update the database credentials in `Includes/header.php` to match your database settings:

   ```php
   $host = "localhost";
   $dbname = "Rezerva";
   $username = "your-username";
   $password = "your-password";
   ```

2. In `index.php`, specify the allowed origins to match your frontend application's domain:

   ```php
   header("Access-Control-Allow-Origin: http://your-frontend-domain.com");
   ```

3. Ensure your web server (e.g., Apache, Nginx) is correctly configured to serve PHP scripts.
---
