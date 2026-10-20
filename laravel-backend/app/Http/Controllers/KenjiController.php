<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KenjiController extends Controller
{

    public $a = 1;
    public $course = "BSIT";
    public function index()
    {
        $student = "Kenji";
        $message = "Hello, $student!";

        return response()->json([
            "message" => "Index",
            "student" => $student,
            "course" => $this->course,
        ]);
    }

    public function read()
    {
        $greet = $this->greet("Kenji");
        return response()->json([
            "greetings" => $greet,
            "message" => "Read"
        ]);
    }

    public function greet($name)
    {
        return response()->json([
            "message" => "Hello, $name!"
        ]);
    }
}