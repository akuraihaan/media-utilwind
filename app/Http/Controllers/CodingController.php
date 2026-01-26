<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CodingSubmission;

class CodingController extends Controller
{
  public function submit(Request $request)
  {
    $passed = str_contains($request->code,'<div');

    CodingSubmission::create([
      'user_id'=>auth()->id(),
      'coding_exercise_id'=>$request->exercise_id,
      'submitted_code'=>$request->code,
      'is_passed'=>$passed
    ]);

    return response()->json(['passed'=>$passed]);
  }
}
