# 🔍 Lost and Found Management System

A web-based platform built with PHP and MySQL designed to streamline reporting, tracking, and retrieving lost or found items within a college, campus, or community environment.

---

## 📌 Table of Contents
* [Features](#-features)
* [File Directory & Structure](#-file-directory--structure)
* [Tech Stack](#-tech-stack)
* [Database Setup](#-database-setup)
* [Installation & Local Setup](#-installation--local-setup)
* [How It Works](#-how-it-works)
* [Author](#-author)

---

## 🌟 Features

### 👤 User & Student Capabilities
* **Authentication:** Account registration (`register.php`) and secure user login (`login.php`, `LOGIN1.PHP`).
* **Report Lost Items:** Submit posts detailing lost items (`lost_post.php`).
* **Report Found Items:** Upload entries for items discovered around campus (`found_post.php`).
* **Browse Postings:** View current lost and found feeds (`lost_show.php`, `found_show.php`, `lost_index.php`).

### 🎓 Faculty Features
* Dedicated post submission portal (`faculty_post.php`).
* Specialized directory to view faculty-related postings (`faculty_show.php`).

### 🛡️ Admin Dashboard & Management
* Secure administrator authentication (`admin.php`).
* Central administration control panel (`admin_home.php`).
* Post moderation and listing management (`admin_manage_posts.php`).
* Analytics and system metrics (`stats.php`).

---

## 📂 File Directory & Structure

```text
lost-and-find/
│
├── admin.php               # Admin login form
├── admin_home.php          # Admin dashboard interface
├── admin_manage_posts.php  # Admin moderation interface for lost/found posts
├── auth.php                # Authentication check and session handler
├── config.php              # Global configurations & constants
├── db.php                  # Database connection setup
├── faculty_post.php        # Form for faculty to post lost/found items
├── faculty_show.php        # Display page for faculty-submitted items
├── found_post.php          # Form to report a found item
├── found_show.php          # Feed display for found items
├── image.png               # Project media asset / logo
├── index.html              # Project landing page (HTML)
├── index.php               # Main homepage redirect & landing portal
├── login.php               # User login form
├── LOGIN1.PHP              # Secondary/Alternative login handler
├── lost_index.php          # Lost items main portal
├── lost_post.php           # Form to report a lost item
├── lost_show.php           # Feed display for lost items
├── register.php            # User registration page
├── sql.php                 # SQL queries and database helper logic
├── stats.php               # Portal analytics & report statistics
└── stdnav.php              # Student navigation bar template component
