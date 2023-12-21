<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogInController extends Controller
{
    public function index() {
        if(env('APP_ENV') === 'production') {
            $manifestPath = public_path('dist/manifest.json');

            $jsFileName = "index.js";
            $cssFileName = "index.css";

            if (file_exists($manifestPath)) {
                $manifestData = json_decode(file_get_contents($manifestPath), true);
                $jsFileName = $manifestData["index.html"]["file"];
                $cssFileName = $manifestData["index.css"]["file"];
            }

            return view('app', compact(
                'jsFileName',
                'cssFileName',
            ));
        }
        return view('app');
    }
}
