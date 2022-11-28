<?php

namespace App\Repositories\Docs;


use Illuminate\Support\Facades\DB;
use App\Models\Docs\Help;
use App\Models\Docs\HelpCategory;
use App\Repositories\Docs\HelpCategoryRepository;

class HelpRepository
{

    public function searchHelp($request)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $group  = isset($request['group']) ? $request['group'] :'user';
        
        $data = Help::where(['locale'=>$locale, 'group'=>$group]);
        if(isset($request['q'])){
            $q= $request['q'];
            $data->whereRaw("title LIKE '%$q%' or content LIKE  '%$q%' or keywords LIKE '%$q%'");
        }
        
        return $data->get();
    }

    public function SearchHelpSlug($request,$slug)
    {
        $fun = new HelpCategoryRepository();
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $group  = isset($request['group']) ? $request['group'] :'user';

        // $category = HelpCategory::where(['locale'=>$locale, 'group'=>$group, 'slug'=>$slug])->first();
        $category = $fun->GetHCategorySlug($request,$slug)->first();
         
        $view = DB::table('docs.help_category')->where(['locale' => $locale, 'group' => $group,'slug' => $slug])->first();
        $view->isSingle = true;
        // $q = isset($request['q']) ? $request['q'] :"";
        // $data = $this->SearchHelpByCategori($d->id);
        
        // Help::where(['category'=>$category->id]);
        if(isset($request['q'])){
            $view->q = $request['q'];

        //     $q= $request['q'];
        //     $data->whereRaw("title LIKE '%$q%' or content LIKE  '%$q%' or keywords LIKE '%$q%'");
        }

        return $view;
    }

    public function SearchHelpByCategori($slug)
    {
        $data = Help::where(['category'=>$slug]);

        return $data->get();
    }
    public function SearchHelpByCategoriQ($q,$slug)
    {
        $data = Help::where(['category'=>$slug])->whereRaw("title LIKE '%$q%' or content LIKE  '%$q%' or keywords LIKE '%$q%'");

        return $data->get();
    }

    public function GetHelpSlugId($request,$slug,$helpId)
    {
        $locale = isset($request['locale']) ? $request['locale'] :'id-ID';
        $group  = isset($request['group']) ? $request['group'] :'user';
        
        $fun = new HelpCategoryRepository();
        $category = $fun->GetHCategorySlug($request,$slug)->first();
        $data = Help::where(['id'=>$helpId,'category'=>$category->id])->first();

        return $data;
    }
    

}
