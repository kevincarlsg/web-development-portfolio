<?php

/**
* Declaración de las rutas para navegacion del sistema
* HASH
* Autor: Equipo de Desarrollo
* Fecha de creación: 27/10/2024
*/


use App\Http\Controllers\ProfileAlumnoController;
use App\Http\Controllers\ProfileProfesorController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\GroupController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\JustificanteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentPaypalController;
use Illuminate\Support\Facades\Mail;

// Route::get('/test-email', function () {
//     try {
//         Mail::raw('Correo de prueba desde Laravel', function ($message) {
//             $message->to('destinatario@correo.com')->subject('Correo de Prueba');
//         });
//         return 'Correo enviado con éxito';
//     } catch (\Exception $e) {
//         return 'Error al enviar el correo: ' . $e->getMessage();
//     }
// });

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::post('/groups/check-schedule-conflict', [GroupController::class, 'checkScheduleConflict'])->name('groups.checkScheduleConflict');

Route::get('/pago', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/pago', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/groups/{group}/exportAttendanceHistory', [GroupController::class, 'exportAttendanceHistory'])->name('groups.exportAttendanceHistory');
Route::post('/groups/validate', [GroupController::class, 'validateGroup'])->name('groups.validate');
Route::get('/groups/{group}/attendance-data', [GroupController::class, 'attendanceData'])->name('groups.attendanceData');
Route::post('/groups/{group}/mark-all-absent', [GroupController::class, 'markAllAbsent'])->name('groups.markAllAbsent');
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/clear', [App\Http\Controllers\NotificationController::class, 'clear'])->name('notifications.clear');
Route::get('/groups/{group}/export-attendance', [GroupController::class, 'exportAttendance'])->name('groups.exportAttendance');
Route::post('/justificantes/aceptar/{id}', [JustificanteController::class, 'aceptar'])->name('justificantes.aceptar');
Route::post('/justificantes/rechazar/{id}', [JustificanteController::class, 'rechazar'])->name('justificantes.rechazar');

Route::get('/api/attendance-summary/{date}', [AttendanceController::class, 'getAttendanceSummary']);
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Ruta principal del dashboard con autenticación
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Rutas específicas para cada rol
Route::get('/dashboard-profesor', [DashboardController::class, 'profesorDashboard'])->name('dashboard-profesor')->middleware(['auth', CheckRole::class . ':profesor']);
Route::get('/dashboard-alumno', [DashboardController::class, 'alumnoDashboard'])->name('dashboard-alumno')->middleware(['auth', CheckRole::class . ':alumno']);

Route::get('/', [AboutController::class, 'index'])->name('about');

// Ruta de prueba para grupos
Route::get('/test-groups', function () {
    $user = Auth::user();

    if ($user) {
        $groups = $user->groups;
        return $groups;
    }

    return 'Usuario no autenticado.';
});

// Rutas específicas para profesores
Route::middleware(['auth', CheckRole::class . ':profesor'])->group(function () {

    Route::get('/perfil-profesor', [ProfileProfesorController::class, 'edit'])->name('profesor.profile.edit');
    Route::patch('/perfil-profesor', [ProfileProfesorController::class, 'update'])->name('profesor.profile.update');
    Route::delete('/perfil-profesor/foto', [ProfileProfesorController::class, 'deletePhoto'])->name('profesor.profile.deletePhoto');

    Route::post('/justificantes/aceptar/{id}', [JustificanteController::class, 'aceptar'])->name('justificantes.aceptar');
    Route::post('/justificantes/rechazar/{id}', [JustificanteController::class, 'rechazar'])->name('justificantes.rechazar');
    Route::get('/attendance-data/{session}', [ProfesorController::class, 'getAttendanceDataBySession'])->name('attendance.data'); 
    Route::get('/dashboard-profesor', [ProfesorController::class, 'index'])->name('profesor.dashboard');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups/{group}/add-alumnos', [GroupController::class, 'addAlumnosForm'])->name('groups.addAlumnosForm');
    Route::delete('/groups/{group}/remove-alumno/{alumno}', [GroupController::class, 'removeAlumno'])->name('groups.removeAlumno');

    // Ruta para guardar los alumnos añadidos al grupo
    Route::post('/groups/{group}/add-alumnos', [GroupController::class, 'addAlumnos'])->name('groups.addAlumnos');
    Route::post('/groups/{group}/import-alumnos-phpspreadsheet', [GroupController::class, 'importAlumnosPhpSpreadsheet'])->name('groups.importAlumnosPhpSpreadsheet');
    
    Route::post('/groups/{group}/generate-qr', [GroupController::class, 'generateQr'])->name('groups.generateQr');  // Generación de QR
    Route::get('/groups/{group}/attendance-qr', [GroupController::class, 'showAttendanceQr'])->name('groups.attendanceQr');  // Visualización del QR

    Route::get('/groups/{group}/manual-attendance', [GroupController::class, 'showManualAttendance'])->name('groups.manualAttendance');
    Route::post('/groups/{group}/store-manual-attendance', [GroupController::class, 'storeManualAttendance'])->name('groups.storeManualAttendance');
});

// Gestión de grupos (profesor y alumno)
Route::middleware(['auth'])->group(function () {
    // Listado de grupos (profesor y alumno)
    Route::get('/schools/{school}/subjects', [SchoolController::class, 'getSubjects'])->name('schools.subjects');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/{group}/students/{alumno}', [GroupController::class, 'showStudent'])->name('groups.showStudent');
});

// Rutas específicas para alumnos
Route::middleware(['auth', CheckRole::class . ':alumno'])->group(function () {

     // Mostrar la página de suscripciones
    Route::get('/paypal', [PaymentPaypalController::class, 'paypal'])->name('paypal');

    // Procesar una orden en PayPal
    Route::post('/paypal-process-order/{order}', [PaymentPaypalController::class, 'paypalProcessOrder'])
        ->name('paypal.process');
    

    // Manejar el éxito del pago y actualizar el usuario
    Route::post('/payment-success', [PaymentPaypalController::class, 'paymentSuccess'])
        ->name('payment.success');

    Route::get('/perfilAlumno', [ProfileAlumnoController::class, 'edit'])->name('profile.edit');
    Route::get('/perfilAlumno', [ProfileAlumnoController::class, 'edit'])->name('profile.edit_alumno');
    Route::patch('/perfilAlumno', [ProfileAlumnoController::class, 'update'])->name('profile.update');
    Route::delete('/perfilAlumno/foto', [ProfileAlumnoController::class, 'deletePhoto'])->name('profile.deletePhoto');
    Route::delete('/perfilAlumno', [ProfileAlumnoController::class, 'destroy'])->name('profile.destroy');
    Route::post('/subir-justificante', [JustificanteController::class, 'store'])->name('justificantes.store');
    Route::get('/alumno/{id}', [AlumnoController::class, 'show'])->name('alumno.show');
    Route::get('/mis-grupos', [GroupController::class, 'indexAlumno'])->name('groups.indexAlumno');
    Route::get('/dashboard-alumno', [AlumnoController::class, 'index'])->name('alumno.dashboard');
    Route::get('/groups/{group}/attendance', [GroupController::class, 'registerAttendance'])->name('groups.attendance');
});

// Ruta para el registro de usuario
Route::get('/register', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.register');
})->name('register');

// Login and Register routes for unauthenticated users
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
});

require __DIR__.'/auth.php';
