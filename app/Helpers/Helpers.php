<?php

use App\Models\Menu;
use Hashids\Hashids;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

if(!function_exists('encodeData')){
    /**
    * Encoding Hashids
    */
    function encodeData(int $id) : String
    {
        $hashIds = new Hashids;
        $id = $hashIds->encode($id);

        return $id;
    }
}

if(!function_exists('decodeData')){
    /**
    * Decoding Hashids
    */
    function decodeData(String $data) : Array
    {
        $hashIds = new Hashids;
        $id = $hashIds->decode($data);

        return $id;
    }
}

if(!function_exists('fileUpload')){
    /**
     * Method for processing single images
     */
     function fileUpload($request, String $name = null, String $filePath = null, String $refCode=null)
     {
         if (!File::exists(public_path($filePath))) {
             File::makeDirectory(public_path($filePath));
         }

         $fileNameToStore = null;

         if ($request->hasFile($name)) {

             $originalTempFile =  $request->file($name);
             $filenamewithextension = $originalTempFile->getClientOriginalName();
             $filename              = pathinfo($filenamewithextension, PATHINFO_FILENAME);
             $extension             = $originalTempFile->getClientOriginalExtension();
             $fileNameToStore       = $refCode === null
                                         ? str_ireplace(' ', '_', $filename).'_'.time(). '.'.$extension
                                         : $refCode.'_'.str_ireplace(' ', '_', $filename).'_'.encodeData(auth()->user()->id).'.'.$extension;
             $originalTempFile->move(public_path($filePath), $fileNameToStore);
         }

         return $fileNameToStore;
     }
 }

 if (!function_exists('isFileExistsInPublicPath')) {
    function isFileExistsInPublicPath(string $filePath = null, string $filename = null) {
        if(file_exists(public_path($filePath.$filename))){
            return $filePath.$filename;
        }else{
            return null;
        }
    }
 }


 if (!function_exists('isFileExistsInStoragePath')) {
    function isFileExistsInStoragePath(string $filePath = null, string $filename = null) {
        if (Storage::exists($filePath.$filename)) {
            return $filePath.$filename;
        } else {
            return null;
        }
    }
 }

if (!function_exists('sidebarMenuList')) {
    function sidebarMenuList() {
        $sidebarMenus = Menu::with([
            'roles:id,name',
            'children:id,name,route,slug,parent_id',
            'children.roles:id,name'
        ])
        ->select('id', 'name', 'route', 'slug', 'parent_id')
        ->where('parent_id', 0)
        ->get()->toArray();

        // return $sidebarMenus;
        return ['sidebarMenus' => $sidebarMenus];
    }
}


if (!function_exists('ngn')) {
    function ngn($amount)
    {
        if (!empty($amount)) {
            return '₦' . number_format($amount);
        } else {
            '—';
        }

    }
}

if (!function_exists('getAndSetUserSession')) {
    function getAndSetUserSession() {
        $user = Http::withToken(session('token'))
            ->get(config('app.api_url').'user/details')->json();

        session()->put('user', $user);
    }
}

if (!function_exists('isMasking')) {
    function isMasking($value, $number=null,$maskChar = 'X') {
        $leng = strlen($value);
        // $mask = str_repeat($maskChar, ($leng - 4)).substr($value,-4,4);
        $mask = substr_replace($value, str_repeat($maskChar, ($leng - 4)), 6, $leng - 4);
        return $mask;
    }
}
