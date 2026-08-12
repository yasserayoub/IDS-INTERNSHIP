# IT Help Desk System

## Internship Project

An IT Help Desk web application developed as part of the internship project.

The system provides employees with a centralized platform for reporting IT problems, managing support tickets, and receiving assistance through an AI-powered IT Help Desk Assistant.

---

## Project Overview

The IT Help Desk System is designed to make IT support easier to manage for both employees and IT support staff.

Employees can submit IT support tickets, describe their problems, and track their requests. IT staff can manage tickets, review reports, and handle support requests.

The system also includes AI-powered features that help classify tickets, recommend ticket priorities, and provide employees with troubleshooting assistance.

---

## Main Objectives

The main objectives of the project are to:

- Provide a centralized IT support system.
- Allow employees to create and manage support tickets.
- Help IT staff organize and process support requests.
- Automatically classify IT support tickets.
- Automatically determine recommended ticket priority.
- Provide AI-powered troubleshooting assistance.
- Generate reports about IT support activity.
- Export reports to PDF and Excel formats.
- Provide automated tests for important application functionality.

---

# Main Features

## 1. User Authentication

The application provides authenticated access to the IT Help Desk system.

Users can access functionality according to their role within the system.

---

## 2. IT Support Tickets

Employees can create support tickets describing their IT problems.

Tickets can contain information such as:

- Ticket title
- Problem description
- Category
- Priority
- Status
- Other relevant ticket information

IT staff can manage and process employee support requests.

---

## 3. AI Help Desk Assistant

The system includes an AI-powered IT Help Desk Assistant.

Employees can describe their IT problem using natural language.

For example:

> My laptop cannot connect to Wi-Fi.

The AI assistant provides troubleshooting instructions and guides the employee through possible solutions.

The assistant can help with common IT problems such as:

- Computers and laptops
- Wi-Fi and network problems
- VPN problems
- Printers
- Email problems
- Password and account problems
- Software problems
- Basic troubleshooting

---

## 4. AI Ticket Category Classification

The system includes automatic ticket category classification.

When an employee creates a ticket, the application analyzes the ticket information and determines an appropriate category.

Examples include:

- Network
- Hardware
- Software
- Email
- Printer
- VPN
- Account or password issues

This helps IT staff organize tickets more efficiently.

---

## 5. AI Ticket Priority Classification

The application also includes automatic ticket priority classification.

The system analyzes ticket information and recommends an appropriate priority.

This helps IT support staff identify tickets that may require faster attention.

---

## 6. Reports

The system provides reporting functionality for IT support activity.

Reports can be used to review support tickets and analyze help desk activity.

---

## 7. PDF Reports

The application supports generating reports in PDF format.

This allows reports to be saved, shared, and used for documentation.

---

## 8. Excel Export

The application supports exporting report information to Excel.

This makes it easier to analyze and work with support data outside the application.

---

## 9. Automated Tests

Feature tests have been created for important parts of the application.

The project includes tests for:

- Ticket category classification
- Ticket priority classification
- Employee ticket category classification
- Employee ticket priority classification

These tests help verify that the classification functionality works correctly.

---

# Technologies Used

## Backend

- PHP
- Laravel

## Frontend

- Blade Templates
- HTML
- CSS
- JavaScript

## Database

- MySQL

## AI

- OpenAI API

## Reporting

- Laravel DOMPDF
- Laravel Excel

## Development Tools

- Git
- GitHub
- XAMPP
- Composer
- Node.js
- npm

---

# Project Structure

```text
it-help-desk/
│
├── app/
│   ├── Exports/
│   ├── Http/
│   │   └── Controllers/
│   └── Services/
│
├── config/
│
├── public/
│   └── css/
│
├── resources/
│   └── views/
│       ├── ai-chat/
│       ├── layouts/
│       └── reports/
│
├── routes/
│   └── web.php
│
├── tests/
│   └── Feature/
│
├── composer.json
├── composer.lock
└── README.md
```

# Future Improvements

The following improvements could be considered for future versions of the system:

- AI-generated summaries for support tickets.
- AI-generated suggested responses for IT support staff.
- Integration with an internal IT knowledge base.
- Email notifications for ticket updates.
- Real-time notifications for employees and IT staff.
- More advanced reporting and dashboard analytics.
- Improved role and permission management.
- AI-powered detection of duplicate or similar tickets.
- Improved conversation history management for the AI assistant.
- Production monitoring and logging.
- Improved mobile responsiveness.
- Integration with additional enterprise IT support tools.

# Project Demonstration

The final project demonstration can cover:

1. User login
2. Employee dashboard
3. Creating an IT support ticket
4. AI ticket classification
5. AI priority classification
6. AI Help Desk Assistant
7. Ticket management
8. Reports
9. PDF report generation
10. Excel export
11. Automated tests
12. Project structure

# Deployment

The application is prepared for deployment as part of the final internship project.

Deployment requires:

- PHP/Laravel hosting
- MySQL database
- Composer
- Required PHP extensions
- Environment configuration
- OpenAI API configuration
- Database migrations
- Production frontend build

For production deployment:

```env
APP_ENV=production
APP_DEBUG=false
```

The production `.env` file should contain the correct database credentials, application URL, and OpenAI API configuration.

# Final Presentation

The final presentation will demonstrate the main functionality of the IT Help Desk System and explain:

- Project objectives
- System design
- Technologies used
- Ticket management
- AI Help Desk Assistant
- AI ticket classification
- AI ticket priority classification
- Reports
- PDF and Excel exports
- Automated testing
- Project structure
- Deployment preparation

# Conclusion

The IT Help Desk System provides a centralized platform for managing IT support requests while incorporating AI-powered functionality to improve troubleshooting and ticket classification.

The project demonstrates the use of:

- Laravel and PHP
- MySQL
- Blade, HTML, CSS, and JavaScript
- OpenAI API integration
- Automated ticket classification
- Ticket priority classification
- Reporting
- PDF generation
- Excel export
- Automated testing
- Git and GitHub

The system provides a strong foundation for an IT support management application and can be further expanded with additional AI, notification, reporting, and enterprise integration features.

# Internship Assessment

This project was developed as part of the internship assessment.

The final project includes:

- Application development
- AI integration
- IT ticket management
- Automated ticket classification
- Ticket priority classification
- Reporting
- PDF generation
- Excel export
- Automated testing
- Documentation
- Git/GitHub version control
- Deployment preparation
- Final project presentation

# GitHub Repository

[View the project on GitHub](https://github.com/yasserayoub/IDS-INTERNSHIP)
