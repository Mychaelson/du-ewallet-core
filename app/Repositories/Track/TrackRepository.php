<?php

namespace App\Repositories\Track;

use App\Models\Track\Activity;

class TrackRepository
{
    public function __construct(private Activity $activity)
    {
    }

    public function saveActivity($data)
    {
        $this->activity->user_id = $data->user_id;
        $this->activity->activity_screen = $data->activity_screen;
        $this->activity->open_time = $data->open_time;
        $this->activity->leave_time = $data->leave_time;
        $this->activity->next_activity_screen = $data->next_activity_screen;
        $this->activity->save();

        return $this->activity;
    }
}
