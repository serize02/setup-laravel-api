<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index ()
    {
        $students = Student::all();

        if($students->isEmpty()){
            return response()->json(['message' => 'No students found'], 200);
        }

        $data = [
            'students' => $students,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function store (Request $request)
    {
        $validator = Validator($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'language' => 'required'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language
        ]);
        
        if (!$student) {
            $data = [
                'message' => 'Student not created',
                'status' => 500
            ];
        }

        $data = [
            'message' => 'Student created',
            'student' => $student,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show ($id)
    {
        $student = Student::find($id);

        if(!$student){

            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy ($id)
    {
        $student = Student::find($id);

        if(!$student){
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $student->delete();

        $data = [
            'message' => 'Student deleted',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update (Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student)
        {
            $data = [
                'message' => 'Student not found',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validator = Validator($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'language' => 'required'
        ]);

        if ($validator->fails()) 
        {
            $data = [
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

    

        $student->name = $request->name;
        $student->email = $request->email;
        $student->phone = $request->phone;
        $student->language = $request->language;

        $student->save();

        $data = [
            'message' => 'Student updated',
            'student' => $student,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
