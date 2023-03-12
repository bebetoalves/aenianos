<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Exception;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ShowLinkController extends Controller
{
    public function __invoke(Link $link)
    {
        abort_if(request()->header('referer') === null, Response::HTTP_FORBIDDEN);

        if (! $link->active) {
            return redirect()->intended($link->url);
        }

        try {
            if (Http::head($link->url)->failed()) {
                $link->markAsBroken();
            }
        } catch (Exception) {
            $link->markAsBroken();
        }

        return redirect()->intended($link->url);
    }
}
