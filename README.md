# rebalance_fitness_tracker
A fitness tracking app for the gym enthusiast.

# ReBalance Fitness Tracker 🏋️‍♂️

A **full-stack fitness tracking web application** that enables users to log workouts, visualize progress, and receive personalized exercise recommendations. Built with **PHP, MySQL, JavaScript, and Chart.js**, the app demonstrates secure authentication, data visualization, and scalable database design.

## 🚀 Features

* **Workout Logging**: Users can record sets, reps, and weights across multiple exercises.
* **Data Visualization**: Weekly workout trends and muscle group breakdowns displayed with **dynamic Chart.js analytics**.
* **Personalized Recommendations**: Implements a *least-used exercise* algorithm to ensure balanced training across **7+ muscle groups**.
* **Role-Based Authentication**: Separate **user** and **admin** portals with session handling and CRUD functionality.
* **Normalized Database Design**: Schema structured in **3NF** for efficient queries and scalable data storage.

## 🛠️ Tech Stack

* **Frontend**: HTML, CSS, JavaScript/AJAX
* **Backend**: PHP
* **Database**: MySQL (3NF relational schema)
* **Data Visualization**: Chart.js
* **Auth & Security**: PHP sessions, role-based access

## 📂 Project Structure

```
/ReBalance-Fitness-Tracker
│── index.php             # Landing page
│── dashboard.php         # User dashboard with analytics
│── user_login.php        # User authentication
│── admin_login.php       # Admin authentication
│── exercise_manager.php  # CRUD for exercises
│── workouts_db.php       # Database operations (workouts)
│── fitness_tracker.sql   # Database schema
│── /assets               # CSS, JS, and media files
```

<img width="762" height="535" alt="Screenshot 2025-08-16 at 7 20 24 PM" src="https://github.com/user-attachments/assets/13002306-cd5f-45cd-851c-81ff83bd6d6f" />

## ⚡ Getting Started

### Prerequisites

* [XAMPP](https://www.apachefriends.org/) or any PHP/MySQL environment
* PHP ≥ 8.0
* MySQL ≥ 5.7

### Setup Instructions

1. Clone this repository:

   ```bash
   git clone https://github.com/Aki927/ReBalance-Fitness-Tracker.git
   cd ReBalance-Fitness-Tracker
   ```
2. Import the database:

   * Open `phpMyAdmin` (or MySQL CLI).
   * Import `fitness_tracker.sql`.
3. Configure database connection:

   * Update credentials in `database.php`.
4. Start local server:

   * Place project folder inside `/htdocs` if using XAMPP.
   * Run `http://localhost/ReBalance-Fitness-Tracker` in browser.


## 📊 Demo



## 🎯 Why This Project Matters

* Demonstrates **full-stack engineering** skills.
* Covers **authentication, CRUD, analytics, and recommendations**.
* Showcases ability to design **scalable, normalized databases**.
* Practical application in **fitness and wellness tech**.


## 👨‍💻 Author

**Jerome Laranang**

* [LinkedIn](https://linkedin.com/in/jerome-laranang)
* [GitHub](https://github.com/Aki927)


