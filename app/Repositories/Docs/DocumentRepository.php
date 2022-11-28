<?php

namespace App\Repositories\Docs;


use Illuminate\Support\Facades\DB;
use App\Models\Docs\Document;

class DocumentRepository
{

    public function GetDocs($request)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $data = Document::whereRaw(" locale = '$locale'")->get();

        return $data;
    }

    public function GetDocsSlug($request,$slug)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $data = Document::whereRaw("locale = '$locale'")->where('slug',$slug)->first();
        if($data) {
            $data->isSingle = true;
        }

        return $data;
    }
    
}
