<?php

namespace PavelVasilyev\AuthAjax\Controllers\Auth;

trait AjaxRespond
{
    /**
     * Ajax respond (fn):
     */
    protected function respond( bool $ok, $title = null, $message = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'ok' => $ok,
            'modalTitle' => $title,
            'modalBody' => $message
        ]);
    }
}
