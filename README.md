This College ERP program is designed to streamline the management of academic and administrative tasks for students and staff in an educational institution. The system integrates several features to facilitate the efficient handling of essential functions such as attendance tracking, marks management, subject-related documents, and user account creation and login.

Key Features:
User Authentication: The system ensures that only authenticated users can access sensitive information. It provides login functionality and redirects users who are not logged in to the login page.

Student Dashboard:

Attendance Records: Students can view their attendance records, including the status (present, absent) and the date of each class.
Marks Management: Students can access their marks for different subjects, making it easier to monitor academic performance.
Subject Information: The dashboard displays a list of subjects, providing detailed information such as the subject name, instructor details (name, phone, email), and links to relevant documents.
Subject Details Page: Each subject has a dedicated page accessible through the dashboard. It displays the subject name, instructor details, and links to documents. The layout includes an interactive design where clicking on a subject redirects to its detail page.

Home Page: The program features an inviting home page with a linear gradient background, providing a welcoming and visually appealing entry point for users. It includes links to the login page and account creation, ensuring ease of access and user engagement.

Styling and User Experience: The design elements of the program emphasize usability with intuitive navigation, consistent use of fonts, colors, and button styles across pages. The CSS ensures smooth transitions and interactive elements, enhancing the overall user experience.

Overall, this program aims to simplify the management of academic tasks, improve communication between students and instructors, and offer a centralized platform for accessing college-related information efficiently

Here’s a sample **How to Use** text for your project repository:  

---

# How to Use College ERP  

This guide explains how to set up and use the College ERP (Enterprise Resource Planning) system hosted in this repository.  

## Prerequisites  
1. Install [WAMP](https://www.wampserver.com/) or any web server that supports PHP and MySQL.  
2. Ensure your system has the following installed:  
   - PHP (v7.4 or higher)  
   - MySQL or MariaDB  
   - A browser to access the application.  

## Setup  

### 1. Clone the Repository  
Clone the project from GitHub:  
```bash  
git clone https://github.com/SkorpionOP/College-erp.git  
```  

### 2. Place in Web Server Directory  
Move the cloned project folder to the root directory of your web server:  
- For **WAMP**: Place the project in `C:/wamp64/www/`.  
- For **XAMPP**: Place the project in `C:/xampp/htdocs/`.  

### 3. Configure the Database  
1. Open **phpMyAdmin** through your web server interface or directly at `http://localhost/phpmyadmin`.  
2. Create a new database named `college_erp`.  
3. Import the database schema:  
   - Go to the `Import` tab in phpMyAdmin.  
   - Upload the `college_erp.sql` file from the repository's `database` folder.  
4. Ensure the database credentials in the project are correct:  
   - Open the `config.php` or `database.php` file in the project.  
   - Update the credentials if necessary (e.g., `username`, `password`, and `database name`).  

### 4. Start the Web Server  
1. Launch your web server (e.g., WAMP or XAMPP).  
2. Open your browser and navigate to:  
   ```
   http://localhost/College-erp  
   ```

## Features  
- **User Login**: Log in with your credentials to access personalized features.  
- **Data Management**: Manage student, staff, and course-related data through an easy-to-use interface.  
- **Reports and Analytics**: Generate detailed reports and insights.  

## Troubleshooting  
1. **Blank Page/Error**: Ensure the database is set up correctly, and your PHP version is compatible.  
2. **Connection Error**: Verify the database credentials in `config.php` match your setup.  
3. **Missing Tables/Data**: Reimport the `college_erp.sql` file into your database.  

## Contributing  
Feel free to contribute by submitting issues or creating pull requests to improve the project.  

---

Let me know if you need further customization!
