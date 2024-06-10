<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $users = User::where('name', 'like', "%$query%")
                     ->orWhere('id', $query)
                     ->paginate(10); // Paginate the results

        // return view('search-results', compact('users'));
        if ($users) {
            return redirect()->route('dashboard')->with('status', 'search');
        }
    }
}
