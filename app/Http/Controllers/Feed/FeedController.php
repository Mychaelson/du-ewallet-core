<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Feed\Feed_react_user;
use App\Models\Feed\FeedCategory;

use App\Repositories\Feed\FeedRepository;
use PDO;

class FeedController extends Controller
{
    private $FeedRepository;

    public function __construct
    (
        FeedRepository $FeedRepository
    )
    {
    	$this->FeedRepository = $FeedRepository;
    }

    public function postUserReact(Request $request)
    {
        $data = $this->FeedRepository->PostUserReact($request);

        return $data;
    }
    public function getListCategory()
    {
       $data = $this->FeedRepository->GetFeedCategory();
       return $data;
    }

    public function getDetalFeedPublished(Request $request)
    {
        $data = $this->FeedRepository->GetDetailFeed($request);

        return $data;
    }

    public function all_get()
    {
        $data = $this->FeedRepository->get_all_feed();

        return $data;
    }
}
