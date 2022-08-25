<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DarkModeController extends Controller
{
    /**
     * Show specified view.
     *
     * @return RedirectResponse
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function switch()
    {
        session([
            'dark_mode' => !session()->has('dark_mode') || !session()->get('dark_mode')
        ]);

        return back();
    }
}
