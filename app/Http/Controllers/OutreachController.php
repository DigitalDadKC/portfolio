<?php

namespace App\Http\Controllers;

use App\Mail\OutreachMail;
use App\Models\Outreach;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class OutreachController extends Controller
{
    public function __invoke(Request $request)
    {
        Mail::to($request->email)->send(new OutreachMail($request->name, $request->company, $request->email));
        Outreach::create([
            'client_id' => $request->id,
            'date_emailed' => date('Y-m-d')
        ]);

        return redirect()->back();
    }
}
