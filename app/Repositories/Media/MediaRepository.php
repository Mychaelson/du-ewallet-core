<?php

namespace App\Repositories\Media;

use App\Models\Media\Group;
use App\Models\Media\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

class MediaRepository
{
    protected $media;
    protected $group;
    public function __construct(Media $media, Group $group)
    {
        $this->media = $media;
        $this->group = $group;
    }

    public function getMediaByGroup($id)
    {
        $data = DB::table('accounts.media')
            ->select('id', 'filename', 'extension', 'mimetype', 'filesize', 'url', 'user_id', 'disk', 'name', 'group_id')
            ->where('group_id', $id)
            ->get();

        return [
            'success' => true,
            'response_code' => 200,
            'message' => 'Success',
            'data' => $data
        ];
    }

    public function upload(Request $request)
    {
       $user = auth('api')->user()->id;
        $validation = Validator::make($request->all(), [
            'file' => 'required|mimes:webp,jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,txt|max:2048',
            'disk' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'response_code' => 200,
                'message' => 'Error validation',
                'data' => $validation->errors()
            ]);
        }
        try{
            $hashfilename = str_random(30);
            $file = $request->file('file');
            $name = $file->getClientOriginalName();
            $mimetype = $file->getClientMimeType();
            $extension = $file->getClientOriginalExtension();
            // $filesize = $file->getClientSize();
            $filesize = 0;
            $filename = $hashfilename . '.' . $extension;

            if ($request->disk == 'local') {
                $filePath = 'public/upload/' . $filename;
                $url = env('APP_URL') . 'upload/' . $filename;
                $request->file->move(public_path('upload'), $filename);
            } else {
                $path = Storage::disk('s3')->put($filename, file_get_contents($request->file));
                $path = Storage::disk('s3')->url($path);
                $filePath = env('AWS_URL') . $filename;
                $url = $filePath;
            }

            $row = $this->media->create([
                'filename' => $filename,
                'extension' => $extension,
                'mimetype' => $mimetype,
                'filesize' => $filesize,
                'filepath' => $filePath,
                'url' => $url,
                'user_id' => $user,
                'disk' => $request->disk,
                'type' => '',
                'name' => $name,
                'group_id' => $request->input('group_id', null)
            ]);

            return response()->json([
                'success' => true,
                'response_code' => 200,
                'message' => 'success upload',
                'data' => [$row]
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'response_code' => 200,
                'message' => 'upload failed',
                'data' => $e->getMessage()
            ]);
        }

    }
}
