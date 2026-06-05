<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\WebinarController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\LearningPathController;
use App\Http\Controllers\student\StudentController;
use App\Http\Controllers\superadmin\SuperadminController;
use App\Http\Controllers\ActiveUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    
    Route::get('/certificate', [CertificateController::class, 'index'])->name('certificate.index');

    Route::resource('assets', AssetController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('learning_paths', LearningPathController::class);
    Route::get('/courses/export', [CourseController::class, 'export'])->name('courses.export');
    Route::get('/courses/search', [WebinarController::class, 'search'])->name('courses.search');
    Route::resource('webinars', WebinarController::class);
    Route::resource('courses', CourseController::class);
    

    Route::delete('/superadmin/bulk-delete', [SuperadminController::class, 'bulkDelete'])->name('superadmin.bulkDelete');

    Route::resource('superadmin', SuperadminController::class);


    // routes/web.php

    Route::prefix('students')->group(function () {

        Route::get('/paid', [StudentController::class, 'index'])
            ->name('students.paid');
    
        Route::get('/paid/dashboard', [StudentController::class, 'dashboard'])
            ->name('students.paid.dashboard');
    
        Route::get('/create', [StudentController::class, 'create'])
            ->name('students.create');
    
        Route::get('/{student}', [StudentController::class, 'show'])
            ->name('students.show');
    
        Route::get('/{student}/edit', [StudentController::class, 'edit'])
            ->name('students.edit');
    });

    Route::get('/active-users', [ActiveUserController::class, 'index']);
    Route::get('/active-users/data', [ActiveUserController::class, 'data']);
});