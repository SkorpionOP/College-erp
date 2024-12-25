# College ERP (Enterprise Resource Planning)

A web-based application to streamline the management of academic and administrative tasks in a college. This system is designed to improve efficiency by providing distinct roles for Students, Teachers, and Admins.

## Admin Login Details
- **Username**: TheHonouredOne
- **Password**: 1

## Features

### Admin Role:
- Manage user accounts (Students, Teachers, Admins).
- Monitor feedback provided by users.
- Track and update logs of activities.
- Publish notifications to all users.
- Manage timetable and subject details.

### Teacher Role:
- Mark student attendance.
- Provide feedback to the Admin.
- Manage and update student marks.
- Access schedules and notifications.

### Student Role:
- View attendance records.
- Access marks and performance data.
- Submit feedback to the Admin.
- Stay updated with notifications and schedules.

## Tech Stack

- **Frontend**: HTML, CSS, JavaScript (uses external CSS for better structure and maintenance).
- **Backend**: PHP for server-side scripting.
- **Database**: MySQL (via WAMP stack).

## Database Structure

The application interacts with the `college_erp1` database, which consists of the following tables:

- **`activity_log`**: Tracks all user activities.
- **`attendance`**: Maintains attendance records of students.
- **`feedback`**: Stores feedback submitted by students and teachers.
- **`instructors`**: Contains data about teachers.
- **`marks`**: Records students' marks and grades.
- **`notifications`**: Handles announcements and notifications.
- **`subjects`**: Stores subject details.
- **`timetable`**: Manages the scheduling of classes.
- **`users`**: Manages login credentials and user roles.

## Installation Guide

1. Clone this repository:
   ```bash
   git clone https://github.com/SkorpionOP/College-erp.git
   ```

2. Install WAMP or any LAMP stack on your system.

3. Import the `college_erp1.sql` file into your MySQL server to set up the database:
   - Open phpMyAdmin.
   - Create a database named `college_erp1`.
   - Import the SQL file.

4. Configure the database connection in `db_connect.php`:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "college_erp";
   ```

5. Start the server and access the application:
   - Place the project folder in the `www` or `htdocs` directory of your server.
   - Open the browser and navigate to `http://localhost/College-erp`.

## Usage

1. **Admin Login**:
   - Manage all aspects of the system, including user accounts and notifications.

2. **Teacher Login**:
   - Manage attendance and marks for students.

3. **Student Login**:
   - View personal records, including marks, attendance, and notifications.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add a new feature"
   ```
4. Push the branch:
   ```bash
   git push origin feature-name
   ```
5. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contact

For any queries or issues, feel free to contact:

- **Author**: SkorpionOP
- **GitHub**: [https://github.com/SkorpionOP](https://github.com/SkorpionOP)

---

Thank you for using College ERP! Your feedback and contributions help us improve.
