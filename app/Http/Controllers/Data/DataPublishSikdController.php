<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataPublishSikdController extends Controller
{
    public function rap_otsus_bg(Request $request)
    {
        return view('data/publish/rap/otsus-bg', [
            'app' => [
                'title' => 'DATA PUBLLISH',
                'desc' => 'Data Publish RAP pada Aplikasi SIKD Otsus DJPK',
            ],
        ]);
    }
}
