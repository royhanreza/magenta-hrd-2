<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QuotationEventController extends Controller
{
    public function index()
    {
        $response = Http::get(env('BASE_URL_EO') . 'api/quotation/final');
        $quotations = $response->json();
        return view('quotation-event.index', ['quotations' => $quotations['data']]);
        // return $quotations['data'];
        // abort(500);
    }
}
