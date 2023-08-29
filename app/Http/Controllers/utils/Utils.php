<?php
namespace App\Http\Controllers\Utils;

class Utils
{
    public static string $URL_FRONT= "http://localhost:3000/auth";

    /*
    * http://localhost:3000/
    * @var $URL_FRONT_BASE
    */
    public static string $URL_FRONT_BASE= "http://localhost:3000/";

    public function redirect(){
        return redirect(self::$URL_FRONT . '/login', 201);
    }
}
