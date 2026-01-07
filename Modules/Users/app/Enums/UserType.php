<?php

namespace Modules\Users\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case AcademicStaff = 'academic_staff';
    case Student = 'student';
}
