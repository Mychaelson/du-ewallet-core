<?php

namespace App\Repositories\Feed;

use App\Models\Feed\Feed_react_user;
use App\Models\Feed\FeedCategory;
use App\Models\Feed\FeedType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class FeedRepository
{

    private $feedcategory;
    private $feedType;
    private $userreact;
    function __construct(FeedCategory $feedcategory, FeedType $feedType,Feed_react_user $userreact)
    {
        $this->feedcategory = $feedcategory;
        $this->feedType = $feedType;
        $this->userreact = $userreact;
    }

    public function GetFeedCategory()
    {
        $data = array();
        $category = $this->feedcategory->select('id', 'category_name', 'category_type', 'category_id', 'created_at', 'updated_at')
            ->get('feed_category');


        foreach ($category as $c) {
            $type = array();
            $subcategory = array();
            $type = $this->feedType
                ->select('id', 'feed_type')
                ->where('id', array('id' => $c->category_type))
                ->get();
            $c->category_type = $type;
            $squery = $this->feedcategory
                ->select('id')
                ->where('category_id', array('category_id' => $c->id))
                ->get();

            if ($squery && $squery->count() > 0) $subcategory = $squery->toArray();
            $c->subcategory = $subcategory;
            $data[] = $c;
        }
        return [
            'success' => true,
            'response_code' => 200,
            'data' => $data
        ];
    }

    public function GetDetailFeed($request)
    {
        $message = "";
        $data = array();
        $zxc = false;
        $url = $request->source;
        $data = array();
        if (!empty($url)) {
            $parse = parse_url($url);
            $slug_array = explode("/", $url);
            $slug = end($slug_array);
            $sources = $parse['host'];
            $cek_cat = DB::table('accounts.feed_source')
                ->select('api_detail', 'status')
                ->where('feed_source', array('feed_source' => [$sources]))
                ->get();
            if ($cek_cat && $cek_cat->count() > 0) {
                if ($cek_cat[0]->status == 1 && !empty($cek_cat[0]->api_detail)) {
                    $api_url_get = str_replace("{slug}", $slug, $cek_cat[0]->api_detail);
                    $api_url = Http::get($api_url_get);
                    if (!empty($api_url) && !empty($api_url["data"])) {
                        $zxc = true;
                        $raw_data = $api_url["data"];
                        $data_category = array();
                        if (!empty($raw_data["category"])) {
                            foreach ($raw_data["category"] as $cadata) {
                                $data_category[] = $cadata['name'];
                            }
                        }
                        $data = array(
                            "title" => $raw_data["title"],
                            "slug" => $raw_data["slug"],
                            "writer" => $raw_data["user"]["fullname"],
                            "publisher" => $raw_data["publisher"]["fullname"],
                            "cover" => $raw_data["cover"],
                            "cover_label" => $raw_data["cover_label"],
                            "content" => $raw_data["content"],
                            "published" => $raw_data["published"],
                            "updated" => $raw_data["updated"],
                            "created" => $raw_data["created"],
                            "category" => $data_category,
                        );
                    } else $message = "this source has not any data to shown";
                } else $message = "this source are not published or not having any detail yet";
            } else $message = "source not found";
        } else $message = "put source url for getting any detail";

     return [
            'success' => $zxc,
            'response_code' => 200,
            'data' => $data,
            'message' => $message,
        ];
    }

    public function PostUserReact($request){
        $message = "";
        $user = 1;
        $data = array();
        $zxc = false;
        $react = $request->react;
        $slug = $request->source;

        if (!empty($user)) {
            if (!empty($react) && !empty($slug)) {
                $react = ($react != "love" && $react != "haha" && $react != "wow" && $react != "sad" && $react != "angry") ? "like" : $react;
                $ceus = DB::table('accounts.feed_react_user')
                    ->where('feed_slug', $slug)
                    ->where('created_by', $user)
                    ->get();
                if ($ceus && $ceus->count() > 0) {
                    if ($ceus[0]->react == $react) {
                        $ins = DB::table('accounts.feed_react_user')
                            ->where('id', $ceus[0]->id)
                            ->delete();
                        $message = "you cancel your reaction on this post";
                    } else {
                        $ins = Feed_react_user::findOrFail($ceus[0]->id);
                        $ins->react = $request->react;
                        $ins->save();
                        $message = "you react $react on this post";
                    }
                } else {
                    $ins = new \App\Models\Feed\Feed_react_user;
                    $ins->created_by = $user;
                    $ins->feed_slug = $request->source;
                    $ins->react = $request->react;
                    $ins->save();
                    $message = "you react $react on this post";
                }
                $message = ($ins) ? $message : "something went wrong when you react $react to this post";
                $zxc = ($ins) ? true : false;
            } else $message = "react or slug can't be empty";
        } else {
            $message = "unauthorized";
        }
        return [
            'success' => $zxc,
            'response_code' => 200,
            'message' => $message,
            'data' => $data,

        ];
    }

    function stripTagsInArrayElements($input, $easy = false, $throwByFoundObject = true, $item_array = array())
    {
        if ($easy) {
            $output = array_map(function ($v) {
                return trim(strip_tags($v));
            }, $input);
        } else {
            $output = $input;
            foreach ($output as $key => $value) {
                if (is_string($value)) {
                    $output[$key] = trim(strip_tags($value));
                } elseif (is_array($value)) {
                    if (isset($value["pubDate"])) unset($value["pubDate"]);

                    if (isset($value["category"]) && !is_array($value["category"])) {
                        $value["category"] = array($value["category"]);
                    }

                    if (isset($value["guid"])) {
                        $content_image = "https://via.placeholder.com/600x400.png/000FFF/FFF?text=Default+Image+News";
                        if (!isset($value["enclosure"]) && !isset($value["media_content"])) $value["enclosure"] = array("@atrributes" => array("url" => "https://via.placeholder.com/600x400.png/000FFF/FFF?text=Default+Image+News", "length" => "1504", "type" => "image/png"));
                        else if (!isset($value["enclosure"]) && isset($value["media_content"])) {
                            $value["enclosure"]["@atrributes"] = $value["media_content"]["@attributes"];
                            $type = "image/jpeg";
                            if (!empty($value["media_content"]["@attributes"]["url"])) {
                                $content_image = $value["media_content"]["@attributes"]["url"];
                                $url = $value["media_content"]["@attributes"]["url"];
                                $files = explode("/", $value["media_content"]["@attributes"]["url"]);
                                $image = Thumbnail($url, "contents/assets/images/" . end($files), 200);
                                $value["enclosure"]["@atrributes"]["url"] = base_url() . "contents/assets/images/" . end($files);
                                $exts = (count($files) > 0) ? explode(".", end($files)) : array();
                                $ext = (count($exts) > 0) ? strtolower(end($exts)) : "";
                                switch ($ext) {
                                    case "jpg":
                                        $type = "image/jpeg";
                                        break;
                                    case "png":
                                        $type = "image/png";
                                        break;
                                    case "gif":
                                        $type = "image/gif";
                                        break;
                                    case "webm":
                                        $type = "image/webm";
                                        break;
                                    default:
                                        $type = "image/jpeg";
                                        break;
                                }
                            }
                            $value["enclosure"]["@atrributes"]["length"] = "1054";
                            $value["enclosure"]["@atrributes"]["type"] = $type;
                            unset($value["media_content"], $value["media_title"], $value["media_description"]);
                        } else {
                            $value["enclosure"]["@atrributes"] = $value["enclosure"]["@attributes"];
                            $content_image = (isset($value["enclosure"]["@attributes"]["url"])) ? $value["enclosure"]["@attributes"]["url"] : $content_image;
                            unset($value["enclosure"]["@attributes"]);
                        }
                        $value["content_image"] = $content_image;
                        if (!empty($item_array)) {
                            $value["identity"] = $item_array;
                        }
                    }
                    $output[$key] =  $this->stripTagsInArrayElements($value);
                } elseif (is_object($value) && $throwByFoundObject) {
                    echo 'Object found in Array by key ' . $key;
                }
            }
        }
        return $output;
    }

    public function get_all_feed(){
        $message = "";
        $data = array();
        $zxc = false;
        //  $resp = REST_Controller::HTTP_NOT_FOUND;
        $data = array();
        $cek_cat = DB::table('accounts.feed_category')
            ->select('id', 'category_name', 'category_data', 'latest_feed')
            ->get();

        //   $cek_cat = $this->db->select("id, category_name, category_data, latest_feed")->get("feed_category");
        if ($cek_cat && $cek_cat->count() > 0) {
            foreach ($cek_cat as $ceval) {
                if (empty($ceval->category_data) || $ceval->latest_feed != date("Y-m-d")) {
                    $cek = DB::table('accounts.feed_category_url')
                        ->select('feed_url')
                        ->where('category_id', $ceval->id)
                        ->get();
                    //$cek = $this->db->select("feed_url")->get_where("feed_category_url", array("category_id"=>$ceval->id));
                    $data_category = array();
                    if ($cek && $cek->count() > 0) {
                        foreach ($cek as $cval) {
                            if (!empty($cval->feed_url)) {
                               // $array = json_decode($cval->feed_url);

                                $xmlObject = simplexml_load_file($cval->feed_url);
                                $json = json_encode($xmlObject);
                                $array = json_decode($json, true);

                                $arr_data = $this->stripTagsInArrayElements(array_slice($array["channel"]["item"], 0, 3), false, true, array("title"=>$array["channel"]["title"], "image"=>$array["channel"]["image"]));

                                foreach ($arr_data as $arval) {
                                    $data_category[] = $arval;
                                }
                            }
                        }
                        if (empty($data_category)) $message = "data feed not available";
                        else {
                            shuffle($data_category);
                            $data[$ceval->category_name] = $data_category;
                           $dd = FeedCategory::findOrFail($ceval->id);
                           $dd->category_data = json_encode($data_category);
                           $dd->save();
                            //$this->db->update("feed_category", array("category_data" => json_encode($data_category), "latest_feed" => date("Y-m-d")), array("id" => $ceval->id));
                        }
                    } else $message = "category not found";
                } else {
                    $data[$ceval->category_name] = json_decode($ceval->category_data, TRUE);
                }
            }
        } else $message = "category not found";

        $zxc = true;
        //  $resp = REST_Controller::HTTP_OK;
        return [
            'success' => $zxc,
            'response_code' => 200,
            'message' => $message,
            'data' => $data,
        ];
    }
}
