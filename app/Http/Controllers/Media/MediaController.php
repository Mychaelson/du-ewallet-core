<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Repositories\Media\MediaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MediaController extends Controller
{
    private $MediaRepository;

    public function __construct(MediaRepository $MediaRepository)
    {
        $this->MediaRepository = $MediaRepository;
    }

    public function filesInGroup($id)
    {
        $data = $this->MediaRepository->getMediaByGroup($id);
        return $data;
    }

    public function upload(Request $request)
    {
        $data = $this->MediaRepository->upload($request);
        return $data;
    }
}
