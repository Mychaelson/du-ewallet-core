<?php

namespace App\Repositories\Docs;


use Illuminate\Support\Facades\DB;
use App\Models\Docs\HelpCategory;

class HelpCategoryRepository
{

    public function GetHCategory($request)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $group  = isset($request['group']) ? $request['group'] :'user';
        $data   = HelpCategory::where(['locale' => $locale, 'group' => $group])->get();

        return $data;
    }

    public function GetHCategorySlug($request,$slug)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $group  = isset($request['group']) ? $request['group'] :'user';

        $data   = HelpCategory::where(['locale' => $locale, 'group' => $group,'slug' => $slug]);

        return $data;
    }

    public function GetHCategoryId($id)
    {
        $data   = HelpCategory::where(['id' => $id])->first();

        return $data;
    }

}
