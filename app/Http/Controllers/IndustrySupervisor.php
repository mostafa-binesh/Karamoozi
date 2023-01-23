<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class IndustrySupervisor extends Controller
{
    public function industrySupervisorHome() {
        return response()->json([
            'data' => [
                'total_students_count' => Auth()->user()->industrySupervisor->industrySupervisorStudents->count(),
                'unevaluated_students_count' => Auth()->user()->industrySupervisor->industrySupervisorUnevaluatedStudents->count(),
                // tip: both is correct, first one returns collection, second one returns query
                // 'unread_messages' => auth()->user()->receivedMessages->where('read',false)->count(),
                'unread_messages' => auth()->user()->receivedMessages()->unread()->count(),
            ]
        ],200);
    }
}
