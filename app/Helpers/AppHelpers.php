<?php
namespace App\Helpers;

class AppHelper
{

    const weekDays = [
        0 => "Sunday",
        1 => "Monday",
        2 => "Tuesday",
        3 => "Wednesday",
        4 => "Thursday",
        5 => "Friday",
        6 => "Saturday",
    ];

    const LANGUEAGES = [
        'en' => 'English',
    ];
    const USER_ADMIN = 1;
    const USER_TEACHER = 2;
    const USER_STUDENT = 3;
    const USER_PARENTS = 4;
    const USER_ACCOUNTANT = 5;
    const USER_LIBRARIAN = 6;
    const USER_RECEPTIONIST = 7;
    const ACTIVE = '1';
    const INACTIVE = '0';
    const EMP_TEACHER = AppHelper::USER_TEACHER;
    const EMP_SHIFTS = [
        1 => 'Day',
        2 => 'Night'
    ];
    const GENDER = [
        1 => 'Male',
        2 => 'Female'
    ];

    const BLOOD_GROUP = [
        1 => 'A+',
        2 => 'O+',
        3 => 'B+',
        4 => 'AB+',
        5 => 'A-',
        6 => 'O-',
        7 => 'B-',
        8 => 'AB-',
    ];

    const SUBJECT_TYPE = [
        1 => 'Core',
        2 => 'Electives'
    ];

    const ATTENDANCE_TYPE = [
        0 => 'Absent',
        1 => 'Present'
    ];

    const TEMPLATE_TYPE = [
        1 => 'SMS',
        2 => 'EMAIL',
        3 => 'ID CARD'
    ];

    const TEMPLATE_USERS = [
        AppHelper::USER_TEACHER => "Employee",
        AppHelper::USER_STUDENT => "Student",
        AppHelper::USER_PARENTS => "Parents",
        0 => "System Users"
    ];

    const SMS_GATEWAY_LIST = [
        1 => 'Bulk SMS Route',
        2 => 'IT Solutionbd',
        3 => 'Zaman IT',
        4 => 'MIM SMS',
        5 => 'Twilio',
        6 => 'Doze Host',
        7 => 'Log Locally',
    ];

    const LEAVE_TYPES = [
        1 => 'Casual leave (CL)',
        2 => 'Sick leave (SL)',
        3 => 'Undefined leave (UL)'
    ];

    const MARKS_DISTRIBUTION_TYPES = [
        1 => "Written",
        2 => "MCQ",
        3 => "SBA",
        4 => "Attendance",
        5 => "Assignment",
        6 => "Lab Report",
        7 => "Practical",
    ];

    const GRADE_TYPES = [
        1 => 'A+',
        2 => 'A',
        3 => 'A-',
        4 => 'B',
        5 => 'C',
        6 => 'D',
        7 => 'F',
    ];
    const PASSING_RULES = [1 => 'Over All', 2 => 'Individual', 3 => 'Over All & Individual' ];

}