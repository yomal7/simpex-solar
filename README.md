# Web-Based Platform for Solar Solutions Provider

**2nd Year Group Project**  
**Group:** CS Group 25

## Project Overview

This project is a web-based system designed to transform the solar energy experience by educating customers and guiding them through the entire process, from initial inquiry to system installation and maintenance. This platform aims to address the operational inefficiencies of current manual system by automating and streamlining key business processes.

## Key Features

### For Customers:

  * **Browse and Customize:** View pre-made solar packages or customize solutions based on individual electricity consumption.
  * **Power Calculator:** Estimate the required solar system size.
  * **Online Store:** Purchase individual components.
  * **Secure Payments:** Make payments through bank transfers or cash.
  * **Track Progress:** Monitor the status of orders, service requests, and the multi-phase project progress from quotation to final grid connection.
  * **Document Upload:** Easily upload necessary documents for the approval process.
  * **After-Sales Support:** Request and track after-sales services.
  * **Chat:** Communicate directly with coordinators for support.

### For Internal Staff:

  * **Admin:** Manage the company blog and create manager profiles.
  * **Manager:** View user profiles and monitor tasks performed by various coordinators.
  * **Operations Coordinator:** Manage customer requests, schedule site visits, create quotations, handle payments, and assign tasks to employees.
  * **Supplier Coordinator:** Manage supplier profiles, update inventory, approve customer orders, and communicate with suppliers.
  * **Human Resource Administrator:** Manage employee profiles and handle leave requests.
  * **Employees (Engineer, Clerk, Technician, Delivery Person):** View assigned tasks, request holidays, and perform role-specific functions like system certification and installation.

## Technologies Used

The project is built using the following technologies and architectural patterns:

  * **Frontend:** HTML, CSS, JavaScript
  * **Backend:** PHP
  * **Database:** MySQL
  * **Web Server:** Apache HTTP Server
  * **Architecture:** Model-View-Controller (MVC)
  * **Version Control:** Git & GitHub

## Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

You will need a local server environment like XAMPP.

### Installation

1.  **Clone the repo:**
    ```sh
    git clone https://github.com/yomal7/simpex-solar.git
    ```
2. **Set up the environment:**
	- Place the project folder in XAMPP `htdocs` directory.
	- Import the database from `dev/simpex_db.sql` using phpMyAdmin or MySQL Workbench.
3. **Configure the application:**
	- Edit `app/config/config.php` for database and environment settings.
4. **Run the application:**
	- Start Apache and MySQL from XAMPP.
	- Visit `http://localhost/simpex-solar/` in the browser.

## Team Members

This project was a collaborative effort by the following team members:

  * **Binula Dimantha** 
  * **Oshada Yomal**
  * **Thisum Dinujaya**
  * **Peshani Ranaweera**
