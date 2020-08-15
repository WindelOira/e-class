import Login from 'Views/account/Login.vue'
import Dashboard from 'Views/portal/Dashboard.vue'

import AcademicYears from 'Views/portal/views/general/AcademicYears.vue'
import AcademicYearsList from 'Views/portal/views/general/academic-years/List.vue'
import AcademicYearEditor from 'Views/portal/views/general/academic-years/Editor.vue'
import Classes from 'Views/portal/views/general/Classes.vue'
import ClassesList from 'Views/portal/views/general/classes/List.vue'
import ClassEditor from 'Views/portal/views/general/classes/Editor.vue'
import Levels from 'Views/portal/views/general/Levels.vue'
import LevelsList from 'Views/portal/views/general/levels/List.vue'
import LevelEditor from 'Views/portal/views/general/levels/Editor.vue'
import Sections from 'Views/portal/views/general/Sections.vue'
import SectionsList from 'Views/portal/views/general/sections/List.vue'
import SectionEditor from 'Views/portal/views/general/sections/Editor.vue'
import SectionConsolidatedGrades from 'Views/portal/views/general/sections/ConsolidatedGrades.vue'
import Strands from 'Views/portal/views/general/Strands.vue'
import StrandsList from 'Views/portal/views/general/strands/List.vue'
import StrandEditor from 'Views/portal/views/general/strands/Editor.vue'
import Subjects from 'Views/portal/views/general/Subjects.vue'
import SubjectsList from 'Views/portal/views/general/subjects/List.vue'
import SubjectEditor from 'Views/portal/views/general/subjects/Editor.vue'
import SubjectTracks from 'Views/portal/views/general/SubjectTracks.vue'
import SubjectTracksList from 'Views/portal/views/general/subject-tracks/List.vue'
import SubjectTrackEditor from 'Views/portal/views/general/subject-tracks/Editor.vue'
import Tracks from 'Views/portal/views/general/Tracks.vue'
import TracksList from 'Views/portal/views/general/tracks/List.vue'
import TrackEditor from 'Views/portal/views/general/tracks/Editor.vue'

import Attendance from 'Views/portal/views/record-management/Attendance.vue'
import AttendanceList from 'Views/portal/views/record-management/attendance/List.vue'
import AttendanceEditor from 'Views/portal/views/record-management/attendance/Editor.vue'
import GradingSheets from 'Views/portal/views/record-management/GradingSheets.vue'
import GradingSheetsList from 'Views/portal/views/record-management/grading-sheets/List.vue'
import GradingSheetEditor from 'Views/portal/views/record-management/grading-sheets/Editor.vue'
import ComputationVariables from 'Views/portal/views/record-management/ComputationVariables.vue'
import ComputationVariablesList from 'Views/portal/views/record-management/computation-variables/List.vue'
import ComputationVariableEditor from 'Views/portal/views/record-management/computation-variables/Editor.vue'

import Users from 'Views/portal/views/user-management/Users.vue'
import UsersList from 'Views/portal/views/user-management/users/List.vue'
import UserEditor from 'Views/portal/views/user-management/users/Editor.vue'
import Students from 'Views/portal/views/user-management/Students.vue'
import StudentsList from 'Views/portal/views/user-management/students/List.vue'
import StudentEditor from 'Views/portal/views/user-management/students/Editor.vue'

import Account from 'Views/portal/views/misc/account/Account.vue'
import Settings from 'Views/portal/views/misc/settings/Settings.vue'

const routes = [
    {
        path        : '/',
        name        : 'login',
        component   : Login
    }, {
        path        : '/dashboard',
        name        : 'dashboard',
        component   : Dashboard,
        children    : [
            {
                path        : 'account',
                component   : Account,
                name        : 'account'
            }, {
                path        : 'settings',
                component   : Settings,
                name        : 'settings'
            }, {
                path        : 'academic-years',
                component   : AcademicYears,
                children    : [
                    {
                        path        : '',
                        name        : 'academic-years',
                        component   : AcademicYearsList
                    }, {
                        path        : 'new',
                        name        : 'academic-year_new',
                        component   : AcademicYearEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'academic-year_edit',
                        component   : AcademicYearEditor
                    }
                ]
            }, {
                path        : 'levels',
                component   : Levels,
                children    : [
                    {
                        path        : '',
                        name        : 'levels',
                        component   : LevelsList
                    }, {
                        path        : 'new',
                        name        : 'level_new',
                        component   : LevelEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'level_edit',
                        component   : LevelEditor
                    }
                ]
            }, {
                path        : 'classes',
                component   : Classes,
                children    : [
                    {
                        path        : '',
                        name        : 'classes',
                        component   : ClassesList
                    }, {
                        path        : 'new',
                        name        : 'class_new',
                        component   : ClassEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'class_edit',
                        component   : ClassEditor
                    }
                ]
            }, {
                path        : 'subjects',
                component   : Subjects,
                children    : [
                    {
                        path        : '',
                        name        : 'subjects',
                        component   : SubjectsList
                    }, {
                        path        : 'new',
                        name        : 'subject_new',
                        component   : SubjectEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'subject_edit',
                        component   : SubjectEditor
                    }
                ]
            }, {
                path        : 'subject-tracks',
                component   : SubjectTracks,
                children    : [
                    {
                        path        : '',
                        name        : 'subject-tracks',
                        component   : SubjectTracksList
                    }, {
                        path        : 'new',
                        name        : 'subject-track_new',
                        component   : SubjectTrackEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'subject-track_edit',
                        component   : SubjectTrackEditor
                    }
                ]
            }, {
                path        : 'strands',
                component   : Strands,
                children    : [
                    {
                        path        : '',
                        name        : 'strands',
                        component   : StrandsList
                    }, {
                        path        : 'new',
                        name        : 'strand_new',
                        component   : StrandEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'strand_edit',
                        component   : StrandEditor
                    }
                ]
            }, {
                path        : 'tracks',
                component   : Tracks,
                children    : [
                    {
                        path        : '',
                        name        : 'tracks',
                        component   : TracksList
                    }, {
                        path        : 'new',
                        name        : 'track_new',
                        component   : TrackEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'track_edit',
                        component   : TrackEditor
                    }
                ]
            }, {
                path        : 'sections',
                component   : Sections,
                children    : [
                    {
                        path        : '',
                        name        : 'sections',
                        component   : SectionsList
                    }, {
                        path        : 'new',
                        name        : 'section_new',
                        component   : SectionEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'section_edit',
                        component   : SectionEditor
                    }, {
                        path        : ':id/consolidated-grades/:semester',
                        name        : 'section_consolidated-grades',
                        component   : SectionConsolidatedGrades
                    }
                ]
            }, {
                path        : 'users',
                component   : Users,
                children    : [
                    {
                        path        : '',
                        name        : 'users',
                        component   : UsersList
                    }, {
                        path        : 'new',
                        name        : 'user_new',
                        component   : UserEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'user_edit',
                        component   : UserEditor
                    }
                ]
            }, {
                path        : 'attendances',
                component   : Attendance,
                children    : [
                    {
                        path        : '',
                        name        : 'attendances',
                        component   : AttendanceList
                    }, {
                        path        : 'new',
                        name        : 'attendance_new',
                        component   : AttendanceEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'attendance_edit',
                        component   : AttendanceEditor
                    }
                ]
            }, {
                path        : 'grading-sheets',
                component   : GradingSheets,
                children    : [
                    {
                        path        : '',
                        name        : 'grading-sheets',
                        component   : GradingSheetsList
                    }, {
                        path        : 'new/:section?/:level?/:adviser?/:semester?',
                        name        : 'grading-sheet_new',
                        component   : GradingSheetEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'grading-sheet_edit',
                        component   : GradingSheetEditor
                    }
                ]
            }, {
                path        : 'computation-variables',
                component   : ComputationVariables,
                children    : [
                    {
                        path        : '',
                        name        : 'computation-variables',
                        component   : ComputationVariablesList
                    }, {
                        path        : 'new',
                        name        : 'computation-variables_new',
                        component   : ComputationVariableEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'computation-variables_edit',
                        component   : ComputationVariableEditor
                    }
                ]
            }, {
                path        : 'students',
                component   : Students,
                children    : [
                    {
                        path        : '',
                        name        : 'students',
                        component   : StudentsList
                    }, {
                        path        : ':section_id?/section',
                        name        : 'students_by_section',
                        component   : StudentsList
                    }, {
                        path        : 'new',
                        name        : 'student_new',
                        component   : StudentEditor
                    }, {
                        path        : 'edit/:id',
                        name        : 'student_edit',
                        component   : StudentEditor
                    }
                ]
            }
        ],
        meta        : {
            requiresAuth    : true
        }
    }
]

export default routes