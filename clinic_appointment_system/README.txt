CLINIC APPOINTMENT SYSTEM
==========================

Technology:
- PHP 8+
- MySQL
- HTML/CSS
- PDO prepared statements

Included modules:
1. Dashboard
   - Total doctors
   - Total patients
   - Today's appointment count
   - Today's appointment list

2. Doctors
   - Add new doctor
   - List doctors
   - Filter by specialization
   - Edit/update doctor details
   - Delete doctor

3. Patients
   - Register new patient
   - List/search patients
   - Edit/update patient details
   - Delete patient

4. Appointments
   - Book appointment
   - Edit appointment
   - Change status: Booked / Completed / Cancelled
   - Delete appointment
   - Filter by doctor, patient, and date

SETUP IN XAMPP
==============
1. Install/start XAMPP.
2. Start Apache and MySQL.
3. Extract this folder into:
   C:\xampp\htdocs\clinic_appointment_system
4. Open phpMyAdmin:
   http://localhost/phpmyadmin
5. Import the file:
   database.sql
   OR open it in phpMyAdmin SQL and run it.
6. Check config.php:
   host = localhost
   db   = clinic_appointment
   user = root
   pass = ""
   If your MySQL root account has a password, change $pass.
7. Open:
   http://localhost/clinic_appointment_system/

IMPORTANT
=========
The sample records in database.sql are only demo data. You can delete them after testing.

The project is designed for a college practical/exam demonstration and covers all requirements shown in the supplied project statement.
