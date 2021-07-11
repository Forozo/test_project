<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function upload()
    {
        $bookByte = file_get_contents('https://alibolaghi.com/bagh_store/admin_area/book_files/0.pdf');

        $path = Storage::disk('public')->put('saeed/saeed.pdf',$bookByte);

        return $path;
    }
    function download()
    {
//        $file= public_path(). '\storage\book_files_lock\5995.pdf';
//
//        $headers = array(
//            'Content-Type: application/pdf',
//        );

        return Storage::disk('public')->get('saeed/saeed.pdf');
    }
}
