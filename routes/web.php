<?php

use Homeful\Prospects\Actions\AuthenticateProspectAction;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('process-buyer', function (Request $request) {
    $prospect = AuthenticateProspectAction::run($request->all());
    return response()->json([
        'code' => $prospect->reference_code,
        'status' => $prospect->exists,
    ]);
})
    ->name('process-buyer');

Route::post('authenticate-prospect', function (Request $request) {
    $prospect = AuthenticateProspectAction::run($request->all());
    return response()->json([
        'code' => $prospect->reference_code,
        'status' => $prospect->exists,
    ]);
})
    ->name('authenticate-prospect');
