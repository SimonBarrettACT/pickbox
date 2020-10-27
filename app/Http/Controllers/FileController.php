<?php

namespace App\Http\Controllers;

use App\Models\Thing;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request) {
        $thing = Thing::with('children.thingable', 'ancestorsAndSelf.thingable')->forCurrentTeam()->where(
            'uuid', $request->get('uuid', Thing::forCurrentTeam()->whereNull('parent_id')->first()->uuid)
        )
            ->firstOrFail();

        return view('files', [
            'thing' => $thing,
            'ancestors' => $thing->ancestorsAndSelf()->breadthFirst()->get()
        ]);
    }
}
