<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhpReview extends Controller
{
    public function review()
    {
        // ==========================================
        // 1. DATA TYPES
        // ==========================================

        $a = 100;
        // Integer: whole number
        // Example: 10, -5, 100

        $b = 3.14;
        // Float: decimal number
        // Example: 3.14, -2.5

        $c = false;
        // Boolean: true or false
        // Used for yes/no or true/false conditions

        $d = "Hello, World!";
        // String: text or characters
        // Text is enclosed in quotes

        $e = ["PHP", "Laravel", "JavaScript"];
        // Array: collection of multiple values
        // Index starts at 0
        // $e[0] = PHP
        // $e[1] = Laravel
        // $e[2] = JavaScript


        // ==========================================
        // 2. COMPOUND DATA TYPE: ARRAY
        // ==========================================

        $array = ["PHP", "Laravel", "JavaScript"];
        // Indexed array
        // Used to store multiple related values

        $f = [
            "name" => "Maria",
            "age" => 21,
            "course" => "BSIT"
        ];
        // Associative array
        // Uses key => value
        // $f["name"]   = Maria
        // $f["age"]    = 21
        // $f["course"] = BSIT


        // ==========================================
        // 3. OBJECT
        // ==========================================

        // Object is an instance of a class.
        // Objects store data and methods together.
        //
        // Example:
        //
        // $student = new Student();
        // $student->name = "Maria";


        // ==========================================
        // 4. ARITHMETIC OPERATORS
        // ==========================================

        $x = 10;
        $y = 3;

        $addition = $x + $y;
        // + Addition
        // 10 + 3 = 13

        $subtraction = $x - $y;
        // - Subtraction
        // 10 - 3 = 7

        $multiplication = $x * $y;
        // * Multiplication
        // 10 * 3 = 30

        $division = $x / $y;
        // / Division
        // 10 / 3 = 3.333...

        $remainder = $x % $y;
        // % Modulus
        // Gets the remainder
        // 10 % 3 = 1

        $power = $x ** $y;
        // ** Exponent
        // 10 ** 3 = 1000

        $x++;
        // ++ Increment
        // Adds 1 to the value

        $y--;
        // -- Decrement
        // Subtracts 1 from the value


        // ==========================================
        // 5. ASSIGNMENT OPERATORS
        // ==========================================

        $number = 10;
        // = Assigns a value

        $number += 5;
        // += Adds 5 to the existing value
        // 10 + 5 = 15

        $number -= 3;
        // -= Subtracts 3
        // 15 - 3 = 12

        $number *= 2;
        // *= Multiplies by 2
        // 12 * 2 = 24

        $number /= 4;
        // /= Divides by 4
        // 24 / 4 = 6

        $number %= 4;
        // %= Gets the remainder
        // 6 % 4 = 2

        $number **= 3;
        // **= Raises the value to a power
        // 2 ** 3 = 8

        $text = "Hello";
        $text .= " World";
        // .= Concatenates strings
        // Result: Hello World


        // ==========================================
        // 6. COMPARISON OPERATORS
        // ==========================================

        $age = 18;

        $age == 18;
        // == Equal in value

        $age != 20;
        // != Not equal

        $age === 18;
        // === Identical
        // Same value AND same data type

        $age !== "18";
        // !== Not identical
        // Value or data type is different

        $age < 20;
        // < Less than

        $age > 16;
        // > Greater than

        $age <= 18;
        // <= Less than or equal

        $age >= 18;
        // >= Greater than or equal


        // ==========================================
        // 7. LOGICAL OPERATORS
        // ==========================================

        $logicalAge = 20;
        $hasID = true;

        if ($logicalAge >= 18 && $hasID == true) {
            // && AND
            // Both conditions must be true

            $access = "Allowed";
        }

        if ($logicalAge >= 18 || $hasID == false) {
            // || OR
            // At least one condition must be true

            $message = "Condition met";
        }

        $isStudent = true;

        if (!$isStudent) {
            // ! NOT
            // Reverses true to false
            // Reverses false to true

            $studentMessage = "Not a student";
        }


        // ==========================================
        // 8. BITWISE OPERATORS
        // ==========================================

        $bitwiseA = 5;
        $bitwiseB = 3;

        $and = $bitwiseA & $bitwiseB;
        // & Bitwise AND

        $or = $bitwiseA | $bitwiseB;
        // | Bitwise OR

        $xor = $bitwiseA ^ $bitwiseB;
        // ^ Bitwise XOR

        $not = ~$bitwiseA;
        // ~ Bitwise NOT

        $left = $bitwiseA << 1;
        // << Left shift

        $right = $bitwiseA >> 1;
        // >> Right shift


        // ==========================================
        // 9. STRING OPERATORS
        // ==========================================

        $firstName = "Maria";
        $lastName = "Santos";

        $fullName = $firstName . " " . $lastName;
        // . Concatenation
        // Combines two or more strings
        // Result: Maria Santos

        $fullName .= " - BSIT";
        // .= Adds another string
        // Result: Maria Santos - BSIT


        // ==========================================
        // 10. ARRAY OPERATORS
        // ==========================================

        $array1 = [
            "name" => "Maria",
            "age" => 21
        ];

        $array2 = [
            "course" => "BSIT",
            "year" => 2
        ];

        $student = $array1 + $array2;
        // + Combines arrays
        // Keeps the keys from the first array


        // ==========================================
        // 11. CONDITIONAL STATEMENTS
        // ==========================================


        // ------------------------------------------
        // IF STATEMENT
        // ------------------------------------------

        $temperature = 35;

        if ($temperature > 30) {
            // Checks if the temperature is greater than 30

            $weather = "Hot";
        }


        // ------------------------------------------
        // IF...ELSE STATEMENT
        // ------------------------------------------

        $age = 17;

        if ($age >= 18) {
            // If age is 18 or above

            $status = "Adult";

        } else {
            // If age is below 18

            $status = "Minor";
        }

        // Example:
        // 17 = Minor
        // 18 = Adult
        // 25 = Adult


        // ------------------------------------------
        // IF...ELSEIF...ELSE STATEMENT
        // ------------------------------------------

        $grade = 85;

        if ($grade >= 90) {

            $remarks = "Excellent";

        } elseif ($grade >= 80) {

            $remarks = "Very Good";

        } elseif ($grade >= 75) {

            $remarks = "Passed";

        } else {

            $remarks = "Failed";
        }

        // 90 and above = Excellent
        // 80-89 = Very Good
        // 75-79 = Passed
        // Below 75 = Failed


        // ------------------------------------------
        // NESTED IF STATEMENT
        // ------------------------------------------

        $studentAge = 20;
        $hasValidID = true;

        if ($studentAge >= 18) {

            // First condition:
            // Student must be 18 or above

            if ($hasValidID == true) {

                // Second condition:
                // Student must have a valid ID

                $studentAccess = "Allowed";

            } else {

                $studentAccess = "Please provide a valid ID";
            }

        } else {

            $studentAccess = "Minor";
        }


        // ------------------------------------------
        // SWITCH STATEMENT
        // ------------------------------------------

        $day = "Monday";

        switch ($day) {

            case "Monday":
                $dayMessage = "Start of the week";
                break;

            case "Tuesday":
                $dayMessage = "Second day";
                break;

            case "Wednesday":
                $dayMessage = "Middle of the week";
                break;

            case "Thursday":
                $dayMessage = "Almost Friday";
                break;

            case "Friday":
                $dayMessage = "Last working day";
                break;

            case "Saturday":
            case "Sunday":
                $dayMessage = "Weekend";
                break;

            default:
                $dayMessage = "Invalid day";
                break;
        }


        // ==========================================
        // 12. LOOPS
        // ==========================================


        // ------------------------------------------
        // FOR LOOP
        // ------------------------------------------
        // Used when you know how many times
        // you want to repeat something.

        $forResult = [];

        for ($i = 1; $i <= 5; $i++) {

            $forResult[] = $i;
        }

        // Result:
        // 1, 2, 3, 4, 5


        // ------------------------------------------
        // WHILE LOOP
        // ------------------------------------------
        // Checks the condition FIRST.
        // If the condition is true, the loop runs.

        $whileResult = [];

        $i = 1;

        while ($i <= 5) {

            $whileResult[] = $i;

            $i++;
        }

        // Result:
        // 1, 2, 3, 4, 5


        // ------------------------------------------
        // DO...WHILE LOOP
        // ------------------------------------------
        // Runs the code FIRST.
        // Then checks the condition.
        // Therefore, it runs at least once.

        $doWhileResult = [];

        $i = 1;

        do {

            $doWhileResult[] = $i;

            $i++;

        } while ($i <= 5);

        // Result:
        // 1, 2, 3, 4, 5


        // ------------------------------------------
        // FOREACH LOOP
        // ------------------------------------------
        // Used mainly for arrays.
        // It goes through each element in the array.

        $foreachResult = [];

        $subjects = ["PHP", "Laravel", "JavaScript"];

        foreach ($subjects as $subject) {

            $foreachResult[] = $subject;
        }

        // Result:
        // PHP
        // Laravel
        // JavaScript


        // ------------------------------------------
        // FOREACH WITH KEY AND VALUE
        // ------------------------------------------
        // Used for associative arrays.

        $studentInfo = [];

        foreach ($f as $key => $value) {

            $studentInfo[$key] = $value;
        }

        // Result:
        // name = Maria
        // age = 21
        // course = BSIT


        // ==========================================
        // 13. RETURN RESULT
        // ==========================================

        return [
            "datatype_integer" => $a,
            "datatype_float" => $b,
            "datatype_boolean" => $c,
            "datatype_string" => $d,
            "datatype_array" => $e,

            "student" => $f,

            "addition" => $addition,
            "subtraction" => $subtraction,
            "multiplication" => $multiplication,
            "division" => $division,
            "remainder" => $remainder,
            "power" => $power,

            "full_name" => $fullName,

            "age_status" => $status,
            "grade_remarks" => $remarks,
            "student_access" => $studentAccess,
            "day_message" => $dayMessage,

            "for_loop" => $forResult,
            "while_loop" => $whileResult,
            "do_while_loop" => $doWhileResult,
            "foreach_loop" => $foreachResult,
            "foreach_key_value" => $studentInfo
        ];
    }
}