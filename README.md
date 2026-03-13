# AlmaInventario – Inventory Management System

## Overview
AlmaInventario is a web-based Inventory Management System developed using PHP and MySQL. The system helps businesses manage products, suppliers, stock transactions, and sales in one platform. It allows users to track inventory, manage stock movement, generate invoices, and export reports.

---

## Features

- User authentication with role-based access
- Dashboard with inventory summary
- Category management
- Supplier management
- Product management
- Stock IN and Stock OUT tracking
- Sales and cart system
- Automatic invoice generation
- PDF invoice export using FPDF
- Inventory and sales reports
- Export reports to CSV, Excel, and PDF

---

## Technologies Used

Frontend
- HTML
- CSS

Backend
- PHP

Database
- MySQL

Libraries
- FPDF

Development Environment
- XAMPP
- phpMyAdmin

---

## Database Tables

- users
- categories
- suppliers
- products
- stock_transactions
- sales
- sale_items

These tables store information related to users, products, suppliers, inventory transactions, and sales.

---

## Folder Structure

AlmaInventario
│
├── css
├── database
│   └── db_connection.php
├── fpdf
├── pages
│   ├── dashboard.php
│   ├── products.php
│   ├── suppliers.php
│   ├── stock_in.php
│   ├── sales.php
│   └── reports.php
│
├── login.php
├── index.php
└── logout.php

---

## How to Run the Project

1. Install XAMPP
2. Copy the project folder into:

C:\xampp\htdocs\

3. Start Apache and MySQL
4. Open phpMyAdmin
5. Create a database named:

almainventario

6. Import the SQL file
7. Run the project in your browser:

http://localhost/AlmaInventario

---

## Future Improvements

- Low stock alerts
- Barcode scanning
- Advanced analytics dashboard
- GST invoice support
- Database backup and restore

---

## Author

Prashanth Nayak Sabhavathula
