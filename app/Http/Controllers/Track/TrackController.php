<?php

namespace App\Http\Controllers\Track;

use App\Http\Controllers\Controller;
use App\Repositories\Track\TrackRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TrackController extends Controller
{
    public function __construct(private TrackRepository $trackRepository)
    {
    }

    public function track(Request $request)
    {
        $this->trackRepository->saveActivity((object) [
            'user_id' => $request->input('user_id', auth()->id()),
            'activity_screen' => $request->input('activity_screen'),
            'open_time' => $request->input('open_time'),
            'leave_time' => $request->input('leave_time'),
            'next_activity_screen' => $request->input('next_activity_screen'),
        ]);

        return response()->json([
            'success' => true,
        ], 200);
    }
}
