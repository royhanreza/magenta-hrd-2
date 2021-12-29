<?php

namespace App\Http\Controllers\api;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TestApiController extends Controller
{
    public function uploadImage(Request $request)
    {
        try {
            $file = base64_decode($request->image);
            $folderName = 'public/uploads/';
            $safeName = Str::random(10) . '.' . 'png';
            $destinationPath = public_path() . $folderName;
            $success = file_put_contents(public_path() . '/images/' . $safeName, $file);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }

        return response()->json([
            'status' => 'OK',
            'message' => $success,
        ]);
    }

    public function helper()
    {
        // try {
        //     echo Helper::test();
        // } catch (Exception $e) {
        //     echo $e->getMessage();
        // }

        // echo 'sadasd';
        return response()->json([
            'message' => Helper::test(),
        ]);
    }

    public function notification()
    {
        return Helper::sendNotification('er0VCSvfSEy4ULKW8k8XW-:APA91bFL-IWWOVvEoaF9ecQ38jod247nITfk-SAp9oCylwOsqFDlYtOmR2G40BsKzmAvIYZMh0VB-_qLkWeeT7VYOiGpTtaf7-aYKFUKiC120kDmIJBJ1wEqkIRpxTPoI8jwVf-fEjWz');
    }
}
