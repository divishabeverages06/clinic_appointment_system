CREATE DATABASE IF NOT EXISTS clinic_appointment CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinic_appointment;

DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;

CREATE TABLE doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    email VARCHAR(120),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gender VARCHAR(20),
    dob DATE NULL,
    phone VARCHAR(20),
    email VARCHAR(120),
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255),
    status ENUM('Booked','Completed','Cancelled') NOT NULL DEFAULT 'Booked',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_appointment_doctor FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_appointment_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_appointment_doctor (doctor_id),
    INDEX idx_appointment_patient (patient_id)
);

INSERT INTO doctors (name,specialization,phone,email,address) VALUES
('Dr. Rahul Shah','Cardiologist','9876543210','rahul@example.com','Ahmedabad'),
('Dr. Neha Patel','Dermatologist','9876543211','neha@example.com','Ahmedabad');

INSERT INTO patients (name,gender,dob,phone,email,address) VALUES
('Amit Kumar','Male','1998-05-12','9876500001','amit@example.com','Ahmedabad'),
('Priya Mehta','Female','2000-08-21','9876500002','priya@example.com','Ahmedabad');

INSERT INTO appointments (doctor_id,patient_id,appointment_date,appointment_time,reason,status) VALUES
(1,1,CURDATE(),'10:00:00','Regular checkup','Booked'),
(2,2,CURDATE(),'11:30:00','Skin consultation','Booked');
