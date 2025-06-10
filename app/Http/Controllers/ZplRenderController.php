<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ZplRenderController extends Controller
{
    public function __invoke(Request $request)
    {
        $zpl   = $request->string('zpl');
        $wDots = $request->integer('dots_w');
        $hDots = $request->integer('dots_h');

        $png = Http::timeout(8)
            ->withHeaders(['Accept' => 'image/png'])
            ->withBody($zpl, 'text/plain')
            ->post("https://api.labelary.com/v1/printers/8dpmm/labels/{$wDots}x{$hDots}/0/")
            ->throw()
            ->body();

        return response()->json([
            'dataUri' => 'data:image/png;base64,' . base64_encode($png),
        ]);
    }
}
