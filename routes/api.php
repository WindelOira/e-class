<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::resource('levels', 'API\LevelController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('levels/{level}', 'API\LevelController@show')->where('level', '[0-9]+');
Route::get('levels/{status}/{filters?}', 'API\LevelController@index')->where('status', '[a-z]+');
Route::patch('levels/{level}/restore', 'API\LevelController@restore')->where('level', '[0-9]+');

Route::resource('classes', 'API\ClassesController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('classes/{class}', 'API\ClassesController@show')->where('class', '[0-9]+');
Route::get('classes/{status}/{filters?}', 'API\ClassesController@index')->where('status', '[a-z]+');
Route::patch('classes/{class}/restore', 'API\ClassesController@restore')->where('class', '[0-9]+');

Route::resource('subjects', 'API\SubjectController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('subjects/{subject}', 'API\SubjectController@show')->where('subject', '[0-9]+');
Route::patch('subjects/{subject}/restore', 'API\SubjectController@restore')->where('subject', '[0-9]+');
Route::get('subjects/{status}/{filters?}', 'API\SubjectController@index')->where('status', '[a-z]+');

Route::resource('subject_tracks', 'API\SubjectTrackController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('subject_tracks/{subject_track}', 'API\SubjectTrackController@show')->where('subject_track', '[0-9]+');
Route::get('subject_tracks/{status}/{filters?}', 'API\SubjectTrackController@index')->where('status', '[a-z]+');
Route::patch('subject_tracks/{subject_track}/restore', 'API\SubjectTrackController@restore')->where('subject_track', '[0-9]+');

Route::resource('strands', 'API\StrandController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('strands/{strand}', 'API\StrandController@show')->where('strand', '[0-9]+');
Route::patch('strands/{strand}/restore', 'API\StrandController@restore')->where('strand', '[0-9]+');
Route::get('strands/{status}/{filters?}', 'API\StrandController@index')->where('status', '[a-z]+');

Route::resource('tracks', 'API\TrackController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('tracks/{track}', 'API\TrackController@show')->where('track', '[0-9]+');
Route::get('tracks/{status}/{filters?}', 'API\TrackController@index')->where('status', '[a-z]+');
Route::patch('tracks/{track}/restore', 'API\TrackController@restore')->where('track', '[0-9]+');

Route::resource('sections', 'API\SectionController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('sections/{section}', 'API\SectionController@show')->where('section', '[0-9]+');
Route::get('sections/{status}/{filters?}', 'API\SectionController@index')->where('status', '[a-z]+');
Route::get('sections/{id}/consolidated-grades', 'API\SectionController@grading_sheets');
Route::patch('sections/{section}/restore', 'API\SectionController@restore')->where('section', '[0-9]+');
Route::get('sections/academic-year/{academic_year?}', 'API\SectionController@get_by_academic_year')->where('academic_year', '[0-9+]');

Route::resource('academic_years', 'API\AcademicYearController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('academic_years/{academic_year}', 'API\AcademicYearController@show')->where('academic_year', '[0-9]+');
Route::get('academic_years/{status}/{filters?}', 'API\AcademicYearController@index')->where('status', '[a-z]+');
Route::patch('academic_years/{academic_year}/restore', 'API\AcademicYearController@restore')->where('academic_year', '[0-9]+');

Route::resource('comments', 'API\CommentController')->except([
    'index', 'create', 'edit'
]);
Route::get('comments/{grading_sheet_id}/{user_id}/', 'API\CommentController@index');

Route::resource('attendances', 'API\AttendanceController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('attendances/{attendance}', 'API\AttendanceController@show')->where('attendance', '[0-9]+');
Route::get('attendances/{status}/{filters?}', 'API\AttendanceController@index')->where('status', '[a-z]+');
Route::patch('attendances/{attendance}/restore', 'API\AttendanceController@restore')->where('attendance', '[0-9]+');

Route::resource('grading_sheets', 'API\GradingSheetController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('grading_sheets/{grading_sheet}', 'API\GradingSheetController@show')->where('grading_sheet', '[0-9]+');
Route::get('grading_sheets/{status}/{filters?}', 'API\GradingSheetController@index')->where('status', '[a-z]+');
Route::patch('grading_sheets/{grading_sheet}/restore', 'API\GradingSheetController@restore')->where('grading_sheet', '[0-9]+');

Route::resource('grading_sheet_approvals', 'API\GradingSheetApprovalController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('grading_sheet_approvals/{grading_sheet_approval}', 'API\GradingSheetApprovalController@show')->where('grading_sheet_approval', '[0-9]+');
Route::get('grading_sheet_approvals/{status}/{filters?}', 'API\GradingSheetApprovalController@index')->where('status', '[a-z]+');
Route::patch('grading_sheet_approvals/{grading_sheet_approval}/restore', 'API\GradingSheetApprovalController@restore')->where('grading_sheet_approval', '[0-9]+');

Route::resource('computation_variables', 'API\ComputationVariableController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('computation_variables/{computation_variable}', 'API\ComputationVariableController@show')->where('computation_variable', '[0-9]+');
Route::get('computation_variables/{status}/{filters?}', 'API\ComputationVariableController@index')->where('status', '[a-z]+');
Route::patch('computation_variables/{computation_variable}/restore', 'API\ComputationVariableController@restore')->where('computation_variable', '[0-9]+');

Route::resource('students', 'API\StudentController')->except([
    'index', 'show', 'create', 'edit',
]);
Route::get('students/{student}', 'API\StudentController@show')->where('student', '[0-9]+');
Route::get('students/{status}/{filters?}', 'API\StudentController@index')->where('status', '[a-z]+');
Route::patch('students/{student}/restore', 'API\StudentController@restore')->where('student', '[0-9]+');

Route::resource('users', 'API\UserController')->except([
    'index', 'create', 'edit'
]);
Route::get('users/{user}', 'API\UserController@show')->where('user', '[0-9]+');
Route::get('users/{status}/{filters?}', 'API\UserController@index')->where('status', '[a-z]+');
Route::patch('users/{user}/restore', 'API\UserController@restore')->where('user', '[0-9]+');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('user', 'API\UserController@details');
    Route::post('logout', 'API\UserController@logout');
});