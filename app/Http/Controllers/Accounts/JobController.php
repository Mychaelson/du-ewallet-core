<?php

namespace App\Http\Controllers\Accounts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

use App\Repositories\Accounts\JobRepository;
use GrahamCampbell\ResultType\Success;

class JobController extends Controller
{
    public function __construct
    (
        private JobRepository $jobRepository
    )
    {}

    public function getJobs(Request $request){
        $response = init_transaction_data($request);
        $jobs = $this->jobRepository->getJobs();

        $response['response']['data'] = $jobs;
        $response['response']['message'] = trans('messages.jobs-found');

        return Response($response['response'])->header('Content-Type', 'application/json');
    }

    public function addJob(Request $request){
        $response = init_transaction_data($request);
        $user = $request->user();

        // get the job and then added to the job table
        $addJob = $this->jobRepository->addJob($user->id, $request->job_id, $request->company);

        $user['job'] = $addJob;

        $response['response']['data'] = $user;
        $response['response']['message'] = trans('messages.job-added');

        return Response($response['response'])->header('Content-Type', 'application/json');
    }
}
