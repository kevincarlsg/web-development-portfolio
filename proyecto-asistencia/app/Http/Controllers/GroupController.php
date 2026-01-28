<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Attendance;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordMail;

class GroupController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == User::ROLE_PROFESOR) {
            $groups = Group::where('profesor_id', Auth::id())->get();
        } else {
            $groups = Auth::user()->groups;
        }

        return view('dashboard.groups.index', compact('groups'));
    }

    public function create()
    {
        if (Auth::user()->role != User::ROLE_PROFESOR) {
            return redirect()->route('groups.index');
        }
        $schools = School::all();
        $subjects = Subject::all();

        return view('dashboard.groups.create', compact('schools', 'subjects'));
    }

    public function store(Request $request)
    {
        // Convertir cadenas vacías a NULL
        $request->merge([
            'school_id' => $request->input('school_id') ?: null,
            'new_school_name' => $request->input('new_school_name') ?: null,
            'subject_id' => $request->input('subject_id') ?: null,
            'new_subject_name' => $request->input('new_subject_name') ?: null,
        ]);

        // Mapeo de nombres de campos para mensajes de error personalizados
        $fieldNames = [
            'name' => 'Nombre del grupo',
            'new_school_name' => 'Nombre de la nueva escuela',
            'new_subject_name' => 'Nombre de la nueva materia',
        ];

        // Función de validación personalizada
        $customNameValidation = function ($attribute, $value, $fail) use ($fieldNames) {
            $fieldName = isset($fieldNames[$attribute]) ? $fieldNames[$attribute] : $attribute;
            // Eliminar espacios adicionales
            $value = trim(preg_replace('/\s+/', ' ', $value));
            // Dividir en palabras
            $words = explode(' ', $value);
            // Verificar número de palabras
            if (count($words) > 6) {
                $fail($fieldName . ' debe tener como máximo 6 palabras.');
                return;
            }
            // Contar dígitos totales en la cadena
            if (preg_match_all('/\d/', $value) > 6) {
                $fail($fieldName . ' debe tener como máximo 6 números en total.');
                return;
            }
            // Validar cada palabra
            foreach ($words as $word) {
                if (!preg_match('/^[\p{L}\p{N}]{2,15}$/u', $word)) {
                    $fail('Cada palabra en ' . $fieldName . ' debe tener entre 2 y 15 caracteres alfanuméricos.');
                    return;
                }
            }
        };

        // Reglas de validación actualizadas
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'school_id' => 'required_without:new_school_name|nullable|exists:schools,id',
            'new_school_name' => [
                'required_without:school_id',
                'nullable',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'subject_id' => 'required_without:new_subject_name|nullable|exists:subjects,id',
            'new_subject_name' => [
                'required_without:subject_id',
                'nullable',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'class_days' => 'required|string|max:255',
            'class_schedule' => 'required|string|max:255',
            'school_period' => 'required|string|max:255',
            'tolerance' => 'nullable|boolean',
            'qr_interval' => 'required|integer|min:1',
        ]);

        if (Auth::user()->role == User::ROLE_PROFESOR) {
            // Extraer los días de clase y el horario
            $classDays = explode(',', $validatedData['class_days']);
            list($startTime, $endTime) = explode(' - ', $validatedData['class_schedule']);

            // Validar que no haya un horario superpuesto con los grupos existentes del profesor
            $existingGroups = Group::where('profesor_id', Auth::id())
                ->where(function ($query) use ($classDays) {
                    foreach ($classDays as $day) {
                        $query->orWhereRaw("FIND_IN_SET(?, class_days) > 0", [$day]);
                    }
                })
                ->get();

            foreach ($existingGroups as $group) {
                list($existingStartTime, $existingEndTime) = explode(' - ', $group->class_schedule);
                if (
                    ($startTime < $existingEndTime && $endTime > $existingStartTime)
                ) {
                    return redirect()->back()->with('error', 'El horario se superpone con otro grupo existente.');
                }
            }

            // Manejo de nueva escuela
            if ($validatedData['new_school_name']) {
                $school = School::create(['name' => $validatedData['new_school_name']]);
                $schoolId = $school->id;
            } else {
                $schoolId = $validatedData['school_id'];
            }

            // Manejo de nueva materia
            if ($validatedData['new_subject_name']) {
                $subject = Subject::create(['name' => $validatedData['new_subject_name']]);
                $subjectId = $subject->id;
            } else {
                $subjectId = $validatedData['subject_id'];
            }

            // Asegurar que 'tolerance' sea booleano
            $tolerance = $request->has('tolerance') ? true : false;

            // Crear el grupo con los datos validados
            $group = Group::create([
                'name' => $validatedData['name'],
                'profesor_id' => Auth::id(),
                'school_id' => $schoolId,
                'subject_id' => $subjectId,
                'class_days' => $validatedData['class_days'],
                'class_schedule' => $validatedData['class_schedule'],
                'school_period' => $validatedData['school_period'],
                'tolerance' => $tolerance,
                'qr_interval' => $validatedData['qr_interval'],
            ]);

            // Agregar notificación para el profesor que ha creado el grupo
            Notification::create([
                'user_id' => Auth::id(),
                'message' => 'Grupo "' . $group->name . '" creado exitosamente.',
                'type' => 'success'
            ]);
        }

        return redirect()->route('groups.index')->with('success', 'Grupo creado exitosamente.');
    }

    public function edit($id)
    {
        $group = Group::with('subject')->findOrFail($id);

        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }

        $schools = School::all();
        $subjects = Subject::all();

        return view('dashboard.groups.edit', compact('group', 'schools', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }

        $request->merge([
            'school_id' => $request->input('school_id') ?: null,
            'new_school_name' => $request->input('new_school_name') ?: null,
            'subject_id' => $request->input('subject_id') ?: null,
            'new_subject_name' => $request->input('new_subject_name') ?: null,
        ]);

        // Mapeo de nombres de campos para mensajes de error personalizados
        $fieldNames = [
            'name' => 'Nombre del grupo',
            'new_school_name' => 'Nombre de la nueva escuela',
            'new_subject_name' => 'Nombre de la nueva materia',
        ];

        // Función de validación personalizada
        $customNameValidation = function ($attribute, $value, $fail) use ($fieldNames) {
            $fieldName = isset($fieldNames[$attribute]) ? $fieldNames[$attribute] : $attribute;
            // Eliminar espacios adicionales
            $value = trim(preg_replace('/\s+/', ' ', $value));
            // Dividir en palabras
            $words = explode(' ', $value);
            // Verificar número de palabras
            if (count($words) > 6) {
                $fail($fieldName . ' debe tener como máximo 6 palabras.');
                return;
            }
            // Contar dígitos totales en la cadena
            if (preg_match_all('/\d/', $value) > 6) {
                $fail($fieldName . ' debe tener como máximo 6 números en total.');
                return;
            }
            // Validar cada palabra
            foreach ($words as $word) {
                if (!preg_match('/^[\p{L}\p{N}]{2,15}$/u', $word)) {
                    $fail('Cada palabra en ' . $fieldName . ' debe tener entre 2 y 15 caracteres alfanuméricos.');
                    return;
                }
            }
        };

        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'school_id' => 'required_without:new_school_name|nullable|exists:schools,id',
            'new_school_name' => [
                'required_without:school_id',
                'nullable',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'subject_id' => 'required_without:new_subject_name|nullable|exists:subjects,id',
            'new_subject_name' => [
                'required_without:subject_id',
                'nullable',
                'string',
                'max:255',
                $customNameValidation,
            ],
            'class_days' => 'nullable|string|max:255',
            'class_schedule' => 'nullable|string|max:255',
            'school_period' => 'nullable|string|max:255',
            'tolerance' => 'nullable|boolean',
            'qr_interval' => 'required|integer|min:1',
        ]);

        if ($validatedData['new_school_name']) {
            $school = School::create(['name' => $validatedData['new_school_name']]);
            $schoolId = $school->id;
        } else {
            $schoolId = $validatedData['school_id'];
        }

        if ($validatedData['new_subject_name']) {
            $subject = Subject::create(['name' => $validatedData['new_subject_name']]);
            $subjectId = $subject->id;
        } else {
            $subjectId = $validatedData['subject_id'];
        }

        $tolerance = $request->has('tolerance') ? true : false;

        $group->update([
            'name' => $validatedData['name'],
            'school_id' => $schoolId,
            'subject_id' => $subjectId,
            'class_days' => $validatedData['class_days'],
            'class_schedule' => $validatedData['class_schedule'],
            'school_period' => $validatedData['school_period'],
            'tolerance' => $tolerance,
            'qr_interval' => $validatedData['qr_interval'],
        ]);

        return redirect()->route('groups.index')->with('success', 'Grupo actualizado correctamente');
    }

    public function addAlumnos(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }

        $request->validate([
            'alumnos' => 'required|array',
            'alumnos.*' => 'exists:users,id',
        ]);

        $group->alumnos()->syncWithoutDetaching($request->alumnos);

        // Agregar notificación para cada alumno agregado al grupo
        foreach ($request->alumnos as $alumnoId) {
            $alumno = User::find($alumnoId);
            Notification::create([
                'user_id' => $alumno->id,
                'message' => 'El profesor ' . Auth::user()->name . ' lo agregó al grupo "' . $group->name . '".',
                'type' => 'info'
            ]);
        }

        return redirect()->route('groups.show', $group->id)->with('success', 'Alumnos añadidos correctamente');
    }

    public function removeAlumno(Group $group, User $alumno)
    {
        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }

        $group->alumnos()->detach($alumno->id);

        // Notificar al alumno sobre su eliminación del grupo
        Notification::create([
            'user_id' => $alumno->id,
            'message' => 'El profesor ' . Auth::user()->name . ' lo eliminó del grupo "' . $group->name . '".',
            'type' => 'warning'
        ]);

        return redirect()->route('groups.show', $group->id)->with('success', 'Alumno eliminado del grupo correctamente.');
    }
    
    
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
    
        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }
    
        // Elimina el grupo
        $group->delete();
    
        return redirect()->route('groups.index')->with('success', 'Grupo eliminado correctamente');
    }

    public function show($id)
    {
        // Encuentra el grupo con las relaciones necesarias, incluyendo alumnos
        $group = Group::with(['school', 'subject', 'alumnos'])->findOrFail($id);
        
        // Verifica que el usuario tenga permiso para ver el grupo
        if (Auth::user()->role != 'profesor' && !$group->alumnos->contains(Auth::user())) {
            return redirect()->route('groups.index');
        }

        // Recupera las fechas únicas de asistencia para este grupo, solo en formato 'YYYY-MM-DD'
        $dates = Attendance::where('group_id', $id)
                    ->selectRaw('DATE(date) as date') // Seleccionar solo la parte de la fecha sin la hora
                    ->distinct()
                    ->orderBy('date', 'asc')
                    ->get()
                    ->pluck('date');

        // Obtener el parámetro de fecha seleccionado y convertirlo a formato Carbon
        $selectedDate = request('selected_date');
        $selectedDateFormatted = $selectedDate ? Carbon::createFromFormat('Y-m-d', $selectedDate)->format('Y-m-d') : null;

        // Inicializar el resumen de asistencia con valores predeterminados
        $attendanceSummary = [
            'totalFaltas' => 0,
            'totalAsistencias' => 0,
            'totalRetardosA' => 0,
            'totalRetardosB' => 0,
        ];

        // Si se seleccionó una fecha, calcular las asistencias de los alumnos para esa fecha
        if ($selectedDateFormatted) {
            logger("Fecha seleccionada: " . $selectedDateFormatted);

            // Obtener los registros de asistencia para cada alumno del grupo en la fecha seleccionada
            $attendances = Attendance::where('group_id', $id)
                                     ->whereDate('date', $selectedDateFormatted)
                                     ->get();

            if ($attendances->isEmpty()) {
                logger("No se encontraron registros de asistencia para la fecha: " . $selectedDateFormatted);
            } else {
                logger("Registros de asistencia encontrados: " . $attendances->count());

                // Calcular el resumen de asistencias basado en los registros encontrados
                foreach ($attendances as $attendance) {
                    switch ($attendance->status) {
                        case 'absent':
                            $attendanceSummary['totalFaltas']++;
                            break;
                        case 'on_time':
                            $attendanceSummary['totalAsistencias']++;
                            break;
                        case 'late_a':
                            $attendanceSummary['totalRetardosA']++;
                            break;
                        case 'late_b':
                            $attendanceSummary['totalRetardosB']++;
                            break;
                    }

                    // Además, verificar los campos 'retardos_tipo_a' y 'retardos_tipo_b'
                    if ($attendance->retardos_tipo_a > 0) {
                        $attendanceSummary['totalRetardosA'] += $attendance->retardos_tipo_a;
                    }

                    if ($attendance->retardos_tipo_b > 0) {
                        $attendanceSummary['totalRetardosB'] += $attendance->retardos_tipo_b;
                    }
                }

                logger("Resumen de asistencia calculado: ", $attendanceSummary);
            }
        } else {
            logger("No se ha seleccionado ninguna fecha.");
        }

        // Generar el código QR solo si el usuario es un profesor
        $qrCode = null;
        $expiration = null;
        if (Auth::user()->role == 'profesor') {
            // Configura la validez del QR según qr_interval
            $expiration = now()->addMinutes($group->qr_interval);
            $qrUrl = route('groups.attendance', [
                'group' => $group->id,
                'expires_at' => $expiration->timestamp,
            ]);

            // Genera el código QR con la URL
            $qrCode = QrCode::size(300)->generate($qrUrl);
        }

        // Retornar la vista con los datos correspondientes
        return view('dashboard.groups.show', compact('group', 'qrCode', 'expiration', 'dates', 'attendanceSummary'));
    }

    public function getAttendanceSummary(Request $request, $id)
    {
        $date = $request->input('date');
        
        // Obtener los datos de asistencia para la fecha y el grupo
        $totalFaltas = Attendance::where('group_id', $id)->where('date', $date)->where('status', 'falta')->count();
        $totalAsistencias = Attendance::where('group_id', $id)->where('date', $date)->where('status', 'asistencia')->count();
        $totalRetardosA = Attendance::where('group_id', $id)->where('date', $date)->where('status', 'retardo_a')->count();
        $totalRetardosB = Attendance::where('group_id', $id)->where('date', $date)->where('status', 'retardo_b')->count();

        $attendanceSummary = [
            'totalFaltas' => $totalFaltas,
            'totalAsistencias' => $totalAsistencias,
            'totalRetardosA' => $totalRetardosA,
            'totalRetardosB' => $totalRetardosB,
        ];

        // Encuentra el grupo para devolver la vista
        $group = Group::with(['school', 'subject', 'alumnos'])->findOrFail($id);
        
        // Recuperar las fechas únicas nuevamente para mostrarlas en el select
        $dates = Attendance::where('group_id', $id)
                    ->select('date')
                    ->distinct()
                    ->orderBy('date', 'asc')
                    ->get()
                    ->pluck('date');

        // Regenerar el QR si es necesario
        $qrCode = null;
        $expiration = null;
        if (Auth::user()->role == 'profesor') {
            $expiration = now()->addMinutes($group->qr_interval);
            $qrUrl = route('groups.attendance', [
                'group' => $group->id,
                'expires_at' => $expiration->timestamp,
            ]);
            $qrCode = QrCode::size(300)->generate($qrUrl);
        }

        return view('dashboard-alumno', compact('group', 'qrCode', 'expiration', 'dates', 'attendanceSummary'));
    }

    public function addAlumnosForm(Request $request, $id)
    {
        // Encuentra el grupo
        $group = Group::findOrFail($id);

        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }


        $search = $request->input('search');

        $alumnos = User::where('role', User::ROLE_ALUMNO)
                    ->when($search, function ($query, $search) {
                        return $query->where('email', 'like', '%' . $search . '%');
                    })
                    ->get();

        return view('dashboard.groups.add-alumnos', compact('group', 'alumnos', 'search'));

        foreach ($request->alumnos as $alumnoId) {
            session()->push('notifications', [
                'message' => 'El profesor ' . Auth::user()->name . ' lo agregó al grupo ' . $group->name,
                'type' => 'info',
                'time' => now()->diffForHumans()
            ]);
        }
    
        return redirect()->route('groups.show', $group->id)->with('success', 'Alumnos añadidos correctamente');
    }

    

    public function indexAlumno()
    {
        $user = Auth::user();
    
        $groups = Group::whereHas('alumnos', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('school','subject', 'profesor')->get();
        

        return view('dashboard.alumno.index', compact('groups'));
    }

    public function importAlumnosPhpSpreadsheet(Request $request, Group $group)
    {
        if (Auth::user()->role != User::ROLE_PROFESOR || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        $filePath = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator(2) as $row) { 
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $data = [];
            foreach ($cellIterator as $cell) {
                $data[] = $cell->getValue();
            }

            if (isset($data[1]) && filter_var($data[1], FILTER_VALIDATE_EMAIL)) {
                // Genera una contraseña aleatoria y segura
                $randomPassword = $this->generateSecurePassword(); // Llamada al método privado
                $alumno = User::firstOrCreate(
                    ['email' => $data[1]], 
                    [
                        'name' => $data[0],   
                        'role' => User::ROLE_ALUMNO,
                        'password' => bcrypt($randomPassword),
                    ]
                );

                // Asocia el alumno al grupo
                $group->alumnos()->syncWithoutDetaching($alumno->id);

                // Envía el correo con la contraseña
                Mail::to($alumno->email)->send(new SendPasswordMail($randomPassword));
            }
        }

        return redirect()->route('groups.show', $group->id)->with('success', 'Alumnos importados correctamente y se enviaron las contraseñas por correo.');
    }

    // Definición de la función generateSecurePassword como un método privado
    private function generateSecurePassword($length = 8)
    {
        $upper = Str::random(1); // Una letra mayúscula
        $lower = strtolower(Str::random(1)); // Una letra minúscula
        $number = rand(0, 9); // Un número
        $specialCharacters = '@$!%*#?&';
        $special = $specialCharacters[rand(0, strlen($specialCharacters) - 1)]; // Un carácter especial

        // Generar los caracteres restantes de manera aleatoria
        $remainingLength = $length - 4;
        $randomString = Str::random($remainingLength);

        // Combinar y mezclar todos los caracteres
        $password = str_shuffle($upper . $lower . $number . $special . $randomString);

        return $password;
    }

    

    public function showAttendanceQr(Group $group)
    {
        if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
            return redirect()->route('groups.index');
        }

        // Genera una URL con el código y almacena el tiempo de creación
        $expiration = now()->addMinutes($group->qr_interval); // Utiliza qr_interval
        $qrUrl = route('groups.attendance', [
            'group' => $group->id, 
            'code' => Str::random(8),
            'expires_at' => $expiration->timestamp,
        ]);

        $qrCode = QrCode::size(300)->generate($qrUrl);

        return view('dashboard.groups.attendance', compact('group', 'qrCode'));
    }

   // Registra la asistencia con control de tiempos
    public function registerAttendance(Request $request, Group $group)
    {
        $user = Auth::user();
    
        // Verificar si el usuario pertenece al grupo
        if (!$group->alumnos->contains($user)) {
            return redirect()->route('dashboard')->withErrors(['No perteneces a este grupo.']);
        }
    
        // Obtener la hora de expiración del QR desde la solicitud y el tiempo actual
        $expiresAt = Carbon::createFromTimestamp($request->query('expires_at'));
        $currentTime = now();
    
        // Verificar si el QR ya ha expirado
        if ($currentTime->greaterThan($expiresAt)) {
            return redirect()->route('groups.show', $group->id)->withErrors(['El código QR ha expirado.']);
        }
    
        // Crear o actualizar el registro de asistencia para el usuario
        $attendance = Attendance::firstOrCreate(
            [
                'group_id' => $group->id,
                'user_id' => $user->id,
                'date' => today(),
            ],
            ['status' => 'absent']
        );
    
        // Determinar la hora de generación del QR (restando el intervalo al tiempo de expiración)
        $qrGeneratedAt = $expiresAt->copy()->subMinutes($group->qr_interval);
    
        // Calcular los minutos de diferencia desde la hora de generación hasta el tiempo actual
        $minutesLate = $qrGeneratedAt->diffInMinutes($currentTime);
    
        // Determinar el estado de asistencia según los minutos transcurridos desde la generación del QR
        if ($minutesLate <= 10) {
            $attendance->status = 'on_time';
        } elseif ($minutesLate <= 20) {
            $attendance->status = 'late_a';
            $attendance->retardos_tipo_a = ($attendance->retardos_tipo_a ?? 0) + 1;
        } elseif ($minutesLate <= 30) {
            $attendance->status = 'late_b';
            $attendance->retardos_tipo_b = ($attendance->retardos_tipo_b ?? 0) + 1;
        } elseif($minutesLate >30){
            $attendance->status = 'absent';
        }
    
        // Guardar los cambios en el registro de asistencia
        $attendance->save();
    
        return redirect()->route('groups.show', $group->id)->with('success', 'Asistencia registrada correctamente.');
    }

    

    public function generateQr(Request $request, Group $group)
    {
        if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'No autorizado.'], 403);
        }

        // Configura la validez del QR según qr_interval
        $expiration = now()->addMinutes($group->qr_interval);
        $qrUrl = route('groups.attendance', [
            'group' => $group->id,
            'expires_at' => $expiration->timestamp,
        ]);

        $qrCode = base64_encode(QrCode::format('svg')->size(300)->generate($qrUrl));

        return response()->json([
            'qrCode' => $qrCode,
            'expiration' => $expiration->timestamp, // Timestamp para el cálculo de tiempo restante
        ]);
    }

    public function storeManualAttendance(Request $request, Group $group)
    {
        if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
            return redirect()->route('groups.index');
        }

        $attendanceData = $request->input('attendance', []);

        foreach ($attendanceData as $userId => $status) {
            // Verifica si el usuario pertenece al grupo y crea o actualiza la asistencia para hoy
            if ($group->alumnos->contains($userId)) {
                $attendance = Attendance::firstOrCreate(
                    [
                        'group_id' => $group->id,
                        'user_id' => $userId,
                        'date' => today(),
                    ],
                    ['status' => 'absent'] // Estado predeterminado en caso de que no se seleccione uno
                );

                // Actualiza el estado de asistencia y los contadores de retardo
                $attendance->status = $status;

                if ($status === 'late_a') {
                    $attendance->retardos_tipo_a++;
                } elseif ($status === 'late_b') {
                    $attendance->retardos_tipo_b++;
                }

                // Convierte los retardos en faltas si alcanza el límite
                if ($attendance->retardos_tipo_a >= 3) {
                    $attendance->retardos_tipo_a = 0;
                    $attendance->status = 'absent';
                }

                if ($attendance->retardos_tipo_b >= 2) {
                    $attendance->retardos_tipo_b = 0;
                    $attendance->status = 'absent';
                }

                $attendance->save();
            }
        }

        return redirect()->route('groups.show', $group->id)->with('success', 'Asistencia registrada manualmente.');
    }
    
    public function showManualAttendance(Group $group)
{
    // Verifica que el usuario autenticado sea el profesor del grupo
    if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
        return redirect()->route('groups.index')->with('error', 'No tienes permiso para acceder a esta sección.');
    }

    $alumnos = $group->alumnos; // Obtiene todos los alumnos del grupo

    // Obtener las asistencias del grupo para la fecha actual
    $today = \Carbon\Carbon::today()->toDateString(); // Obtiene la fecha de hoy en formato Y-m-d
    $attendances = Attendance::where('group_id', $group->id)
        ->where('date', $today)
        ->whereIn('user_id', $alumnos->pluck('id')) // Solo las asistencias de los alumnos de este grupo
        ->get();

    return view('dashboard.groups.manual-attendance', compact('group', 'alumnos', 'attendances'));
}


    public function showStudent(Group $group, User $alumno)
    {
        // Asegurarse de que la relación alumnos esté cargada
        $group->load('alumnos');

        // Permitir el acceso si el usuario es profesor del grupo o el alumno en cuestión
        if (Auth::user()->role !== User::ROLE_PROFESOR && Auth::id() !== $alumno->id) {
            return redirect()->route('groups.index')->withErrors(['No tienes permiso para ver esta información.']);
        }

        // Verificar que el alumno pertenezca al grupo
        if (!$group->alumnos->contains($alumno->id)) {
            return redirect()->route('groups.show', $group->id)->withErrors(['Este alumno no pertenece al grupo.']);
        }

        // Obtener los registros de asistencia del alumno en el grupo
        $attendances = Attendance::where('group_id', $group->id)
            ->where('user_id', $alumno->id)
            ->orderBy('date', 'desc')
            ->get();

        // Calcular totales para la gráfica
        $attendanceCount = $attendances->where('status', 'on_time')->count();
        $lateACount = $attendances->where('status', 'late_a')->count();
        $lateBCount = $attendances->where('status', 'late_b')->count();
        $absentCount = $attendances->where('status', 'absent')->count();

        return view('dashboard.groups.student', compact('group', 'alumno', 'attendances', 'attendanceCount', 'lateACount', 'lateBCount', 'absentCount'));
    }
    public function exportAttendance(Group $group)
    {
        if (Auth::user()->role != 'profesor' || $group->profesor_id != Auth::id()) {
            return redirect()->route('groups.index')->withErrors(['No tienes permiso para exportar la asistencia de este grupo.']);
        }

        $response = new StreamedResponse(function () use ($group) {
            $handle = fopen('php://output', 'w');
            // Encabezados del archivo CSV
            fputcsv($handle, ['Alumno', 'Fecha', 'Estado de Asistencia']);

            // Obtener las asistencias del grupo
            $attendances = Attendance::where('group_id', $group->id)->with('user')->get();

            foreach ($attendances as $attendance) {
                $data = [
                    $attendance->user->name,
                    $attendance->date->format('Y-m-d'),
                    $attendance->status,
                ];
                fputcsv($handle, $data);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="asistencia_grupo_' . $group->name . '.csv"');

        return $response;
    }
    public function markAllAbsent(Group $group)
        {
            // Verificar que el usuario autenticado sea el profesor del grupo
            if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Usar la fecha actual del servidor para determinar la sesión de asistencia
            $currentDate = now()->toDateString();

            // Obtener todos los alumnos del grupo
            $alumnos = $group->alumnos;

            foreach ($alumnos as $alumno) {
                // Verificar si el alumno ya tiene asistencia registrada para la fecha actual
                $attendance = Attendance::where('group_id', $group->id)
                                        ->where('user_id', $alumno->id)
                                        ->where('date', $currentDate)
                                        ->first();

                // Si no hay asistencia registrada, se marca como falta
                if (!$attendance) {
                    Attendance::create([
                        'group_id' => $group->id,
                        'user_id' => $alumno->id,
                        'date' => $currentDate,
                        'status' => 'absent',
                        'retardos_tipo_a' => 0,
                        'retardos_tipo_b' => 0,
                    ]);
                }
            }

            return response()->json(['success' => true]);
        }


    public function attendanceData(Group $group)
    {
        // Verifica que el usuario autenticado sea el profesor del grupo
        if (Auth::user()->role !== 'profesor' || $group->profesor_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $selectedDate = request('date');
    
        // Asegurarse de que la fecha esté presente en la solicitud
        if (!$selectedDate) {
            return response()->json(['error' => 'No date provided'], 400);
        }
    
        // Obtener la asistencia de todos los alumnos del grupo para la fecha seleccionada
        $attendanceRecords = Attendance::where('group_id', $group->id)
                                       ->where('date', $selectedDate)
                                       ->get();
    
        // Preparar los datos para la gráfica y la tabla
        $labels = ['A Tiempo', 'Retardo A', 'Retardo B', 'Falta', 'Justificado'];
        $attendanceCounts = [
            'on_time' => 0,
            'late_a' => 0,
            'late_b' => 0,
            'absent' => 0,
            'justified' => 0
        ];
    
        $attendanceDetails = [];
    
        foreach ($group->alumnos as $alumno) {
            $record = $attendanceRecords->firstWhere('user_id', $alumno->id);
            if ($record) {
                $status = $record->status;
                $attendanceCounts[$status]++;
            } else {
                $status = 'No registrado';
            }
    
            $attendanceDetails[] = [
                'alumno' => $alumno->name,
                'status' => $status
            ];
        }
    
        return response()->json([
            'labels' => $labels,
            'attendance' => array_values($attendanceCounts),
            'attendanceDetails' => $attendanceDetails,
        ]);
    }
    

public function validateGroup(Request $request)
{
    $profesorId = Auth::id(); // ID del profesor autenticado
    $selectedDays = explode(',', $request->class_days); // Días seleccionados
    $startTime = $request->start_time;
    $endTime = $request->end_time;

    // Verificar si ya existe un grupo con el mismo nombre para el profesor
    $groupNameExists = Group::where('name', $request->name)
                            ->where('profesor_id', $profesorId)
                            ->exists();

    // Verificar si hay un conflicto de horario
    $scheduleConflict = Group::where('profesor_id', $profesorId)
                            ->where(function ($query) use ($selectedDays) {
                                foreach ($selectedDays as $day) {
                                    $query->orWhereRaw("FIND_IN_SET(?, class_days)", [$day]);
                                }
                            })
                            ->where(function ($query) use ($startTime, $endTime) {
                                $query->whereBetween('start_time', [$startTime, $endTime])
                                    ->orWhereBetween('end_time', [$startTime, $endTime])
                                    ->orWhere(function ($subQuery) use ($startTime, $endTime) {
                                        $subQuery->where('start_time', '<=', $startTime)
                                                ->where('end_time', '>=', $endTime);
                                    });
                            })
                            ->exists();

    return response()->json([
        'nameExists' => $groupNameExists,
        'scheduleConflict' => $scheduleConflict,
    ]);
}

    
    public function exportAttendanceHistory(Request $request, Group $group)
    {// Obtener los registros de asistencia del grupo
       
        // Obtener los registros de asistencia del grupo
        $attendances = $group->attendances()
            ->join('users', 'attendances.user_id', '=', 'users.id')
            ->select('users.id as student_id', 'users.name as student_name', 'attendances.date', 'attendances.status')
            ->groupBy('users.id', 'users.name', 'attendances.date', 'attendances.status')
            ->orderBy('users.name', 'asc')
            ->get();

        // Obtener las fechas únicas del rango de asistencia
        $dates = $group->attendances()
            ->distinct()
            ->pluck('date')
            ->sort()
            ->toArray();

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezado de Información del Grupo
        $sheet->setCellValue('A1', 'Escuela:');
        $sheet->setCellValue('B1', $group->school->name ?? 'No especificada');
        $sheet->mergeCells('B1:D1'); // Unir celdas para información clara

        $sheet->setCellValue('E1', 'Materia:');
        $sheet->setCellValue('F1', $group->subject->name ?? 'No especificada');
        $sheet->mergeCells('F1:H1'); // Unir celdas para información clara

        $sheet->setCellValue('A2', 'Grupo:');
        $sheet->setCellValue('B2', $group->name);
        $sheet->mergeCells('B2:D2'); // Unir celdas para información clara

        $sheet->setCellValue('E2', 'Profesor:');
        $sheet->setCellValue('F2', $group->profesor->name ?? 'Sin profesor');
        $sheet->mergeCells('F2:H2'); // Unir celdas para información clara

        $sheet->setCellValue('A3', 'Ciclo escolar:');
        $sheet->setCellValue('B3', $group->school_period ?? 'No especificado');
        $sheet->mergeCells('B3:D3'); // Unir celdas para información clara

        // Estilos para el encabezado de información del grupo
        $sheet->getStyle('A1:H3')->getFont()->setBold(true);
        $sheet->getStyle('A1:H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Leyenda de Colores (Descripción + Columna Vacía + Celda con color y símbolo)
        $sheet->setCellValue('I1', 'Leyenda'); // Mover la leyenda a la columna I para espacio extra
        $sheet->mergeCells('I1:L1'); // Unir celdas de leyenda

        $sheet->setCellValue('I2', 'Asistencia');
        $sheet->setCellValue('K2', '✔');
        $sheet->getStyle('K2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00FF00'); // Fondo verde para asistencia
        $sheet->getStyle('K2')->getFont()->setColor(new Color(Color::COLOR_BLACK));

        $sheet->setCellValue('I3', 'Falta');
        $sheet->setCellValue('K3', 'X');
        $sheet->getStyle('K3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000'); // Fondo rojo para falta
        $sheet->getStyle('K3')->getFont()->setColor(new Color(Color::COLOR_BLACK));

        // Ajustar la leyenda en función de la tolerancia del grupo
        if ($group->tolerance) {
            // Si el grupo tiene tolerancia, incluir Retardo A y Retardo B
            $sheet->setCellValue('I4', 'Retardo A');
            $sheet->setCellValue('K4', '!');
            $sheet->getStyle('K4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00'); // Fondo amarillo para Retardo A
            $sheet->getStyle('K4')->getFont()->setColor(new Color(Color::COLOR_BLACK));

            $sheet->setCellValue('I5', 'Retardo B');
            $sheet->setCellValue('K5', '!');
            $sheet->getStyle('K5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFA500'); // Fondo naranja para Retardo B
            $sheet->getStyle('K5')->getFont()->setColor(new Color(Color::COLOR_BLACK));

            $sheet->setCellValue('I6', 'Justificación');
            $sheet->setCellValue('K6', 'J');
            $sheet->getStyle('K6')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0'); // Fondo azul para justificación
            $sheet->getStyle('K6')->getFont()->setColor(new Color(Color::COLOR_BLACK));
        } else {
            // Si no hay tolerancia, mover justificación hacia arriba
            $sheet->setCellValue('I4', 'Justificación');
            $sheet->setCellValue('K4', 'J');
            $sheet->getStyle('K4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('00B0F0'); // Fondo azul para justificación
            $sheet->getStyle('K4')->getFont()->setColor(new Color(Color::COLOR_BLACK));
        }

        // Estilo para la leyenda
        $sheet->getStyle('I1:L6')->getFont()->setBold(true);
        $sheet->getStyle('I1:I6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('K1:K6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Espacio antes de la tabla de asistencia
        $sheet->setCellValue('A7', 'N.º de Orden');
        $sheet->mergeCells('A7:A8'); // Unificar celdas para N.º de Orden
        $sheet->setCellValue('B7', 'Apellidos y Nombres');
        $sheet->mergeCells('B7:B8'); // Unificar celdas para Apellidos y Nombres
        $sheet->getStyle('A7:B8')->getFont()->setBold(true);
        $sheet->getStyle('A7:B8')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFCC'); // Fondo verde claro para encabezado
        $sheet->getStyle('A7:B8')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); // Agregar bordes a cada celda del encabezado

        // Traducción de los días de la semana a español con solo la inicial
        $daysOfWeek = [
            'Mon' => 'L',
            'Tue' => 'M',
            'Wed' => 'X',
            'Thu' => 'J',
            'Fri' => 'V',
            'Sat' => 'S',
            'Sun' => 'D'
        ];

        // Encabezado con las fechas de asistencia, incluyendo el día de la semana en español
        $columnIndex = 3; // Comenzar desde la tercera columna (C)
        foreach ($dates as $date) {
            $dayOfWeek = date('D', strtotime($date));
            $dayInitial = isset($daysOfWeek[$dayOfWeek]) ? $daysOfWeek[$dayOfWeek] : '';
            $dayNumber = date('d', strtotime($date));

            // Combinar el encabezado para la inicial del día y el número de día
            $sheet->setCellValueByColumnAndRow($columnIndex, 7, $dayInitial);
            $sheet->setCellValueByColumnAndRow($columnIndex, 8, $dayNumber);

            // Aplicar estilos
            $sheet->getStyleByColumnAndRow($columnIndex, 7)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyleByColumnAndRow($columnIndex, 8)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyleByColumnAndRow($columnIndex, 7, $columnIndex, 8)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyleByColumnAndRow($columnIndex, 7, $columnIndex, 8)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('CCFFCC'); // Fondo verde claro
            $sheet->getStyleByColumnAndRow($columnIndex, 7, $columnIndex, 8)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN); // Agregar bordes a cada celda del encabezado

            $columnIndex++;
        }

        // Ajustar el ancho de las columnas para mejorar la legibilidad
        $sheet->getColumnDimension('A')->setWidth(15); // Número de Orden (mayor ancho)
        $sheet->getColumnDimension('B')->setWidth(35); // Nombre del estudiante (mayor ancho)
        for ($i = 3; $i < $columnIndex; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setWidth(12); // Ancho mayor para cada fecha
        }

        // Llenar filas de estudiantes con ID y nombre, comenzando desde 1 (fondo blanco para la lista)
        $row = 9; // Comenzar en la fila 9 después del encabezado
        $studentIds = [];
        $studentCount = 1;

        foreach ($attendances->unique('student_id') as $attendance) {
            $sheet->setCellValue('A' . $row, $studentCount);
            $sheet->setCellValue('B' . $row, $attendance->student_name);
            $studentIds[$attendance->student_id] = $row; // Almacena el ID y la fila correspondiente

            // Aplicar estilo a las celdas de ID y nombre de estudiante (fondo blanco)
            $sheet->getStyle('A' . $row . ':B' . $row)->getFill()->setFillType(Fill::FILL_NONE); // Fondo blanco
            $sheet->getStyle('A' . $row . ':B' . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $studentCount++;
            $row++;
        }

        // Añadir unas filas en blanco debajo de la lista de estudiantes para mayor espacio
        $row += 3;

        // Llenar los estados de asistencia con colores de fondo y símbolos ajustados
        foreach ($attendances as $attendance) {
            if (isset($studentIds[$attendance->student_id])) {
                $row = $studentIds[$attendance->student_id];
                $column = array_search($attendance->date, $dates) + 3; // Columna para la fecha específica

                $statusTranslation = '';
                $fillColor = '';

                switch ($attendance->status) {
                    case 'on_time':
                        $statusTranslation = '✔'; // Asistencia
                        $fillColor = '00FF00'; // Verde
                        break;
                    case 'late_a':
                        if ($group->tolerance) {
                            $statusTranslation = '!'; // Retardo A
                            $fillColor = 'FFFF00'; // Amarillo
                        }
                        break;
                    case 'late_b':
                        if ($group->tolerance) {
                            $statusTranslation = '!'; // Retardo B
                            $fillColor = 'FFA500'; // Naranja
                        }
                        break;
                    case 'justified':
                        $statusTranslation = 'J'; // Justificación
                        $fillColor = '00B0F0'; // Azul
                        break;
                    case 'absent':
                        $statusTranslation = 'X'; // Falta
                        $fillColor = 'FF0000'; // Rojo
                        break;
                }

                if ($statusTranslation != '') {
                    $cell = chr(65 + $column - 1) . $row; // Convertir la columna a letra
                    $sheet->setCellValue($cell, $statusTranslation);
                    $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($fillColor);
                    $sheet->getStyle($cell)->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
                    $sheet->getStyle($cell)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                }
            }
        }

        // Aplicar bordes exteriores a todo el rango de la tabla principal
        $lastColumn = chr(65 + $columnIndex - 2);
        $lastRow = $row - 1;
        $sheet->getStyle("A7:{$lastColumn}{$lastRow}")->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

        // Crear el archivo para descargar
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="control_asistencia.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
    

}

