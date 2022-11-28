<?php

namespace App\Http\Controllers\Accounts;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts\SecurityQuestionsRepository;

class SecurityQuestionsController extends Controller
{
    public function __construct
    (
        private SecurityQuestionsRepository $sqRepository
    )
    {}

    public function index(Request $request, $action)
    {
        //init data
        $data = init_transaction_data($request, $action);
        $data['user'] = auth('api')->user();

        //validate fields
        $data = $this->validateFields($data);
        if ( !$data['response']['success'] )
            return response($data['response'])->header('Content-Type', 'application/json');

        //execute
        $data = $this->$action($data);
        
        return response($data['response'])->header('Content-Type', 'application/json');
    }

    private function lists($data)
    {
        $data['response']['data'] =  $this->sqRepository->getSecurityQuestionsByUserId($data['user']->id);
        return $data;
    }

    private function confirm($data)
    {
        $content = $data['request']['content'];

        //get the related questions
        $question = $this->sqRepository->getSecurityQuestionById($content['question_id']);
        if (is_null($question)) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 403;
            $data['response']['message'] = trans('message.question-id-invalid');
            return $data;
        }

        $cleanAnswer = preg_replace("/[^a-z0-9 ]+/", "", strtolower($content['answer']));
        $validateAnswer = $cleanAnswer == $question->answer;
        if (!$validateAnswer) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 403;
            $data['response']['message'] = trans('messages.answer-invalid');
            return $data;
        } else {
            $data['response']['data'] = array(
                'confirm' => true,
                'confirm_code' => generate_confirm_code($data['user']->id)
            );
        }

        return $data;
    }
    
    private function defaults($data)
    {
        $data['response']['data'] =  array(
            'Film favorit anda?',
            'Konser musik favorit?',
            'Pekerjaan yang berdasarkan hobi anda?',
            'Motto hidup anda?'
        );

        return $data;
    }

    private function update($data)
    {
        $contents = $data['request']['content']['questions'];
        foreach ($contents as $key => $content) {
            //check existing question
            $question = $this->sqRepository->getSecurityQuestionsByUserIdAndOrder($data['user']->id, $content['order']);
            if (is_null($question)) {
                //create new security question
                $this->sqRepository->create([
                    'user_id' => $data['user']->id,
                    'question' => preg_replace("/[^a-z0-9 ]+/", "", strtolower($content['question'])),
                    'answer' => preg_replace("/[^a-z0-9 ]+/", "", strtolower($content['answer'])),
                    'order' => $content['order']
                ]);
            } else {
                //should update 
                $updates = array(
                    'question' => preg_replace("/[^a-z0-9 ]+/", "", strtolower($content['question'])),
                    'answer' => preg_replace("/[^a-z0-9 ]+/", "", strtolower($content['answer'])),
                );
                $this->sqRepository->update($question->id, $updates);
            }
        }

        $data['response']['message'] = trans('messages.security-questions-updated');
        return $data;
    }

    private function validateFields($data)
    {
        $content = $data['request']['content'];
        $skipValidation = ['lists', 'defaults'];
        if (in_array($data['action'], $skipValidation)) {
            return $data;
        } elseif ($data['action'] == 'confirm') {
            $rules = array(
                'question_id' => 'required:numeric',
                'answer' => 'required',
            );
        } elseif ($data['action'] == 'update') {
            $rules = array(
                'questions' => 'present|array',
                'questions.*.order' => 'required|numeric',
                'questions.*.question' => 'required',
                'questions.*.answer' => 'required',
            );
        }

        $validator = Validator::make($content, $rules);
        if($validator->fails()) {
            $data['response']['success'] = false;
            $data['response']['response_code'] = 422;
            foreach ($validator->errors()->messages() as $field => $value) {
                foreach ($value as $key => $message) {
                    $data['response']['message'] .= "$message ";
                }
            }
        }

        return $data;
    }
}