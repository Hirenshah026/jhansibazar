<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // DB Facade import
use Session;

class CategoryController extends Controller
{
    // List fetch karne ke liye
    public function index()
    {
        $categories = DB::table('category')->where( 'shop_id', Session::get('shopuser')->id)->orderBy('id', 'desc')->get();
        return view('front.categories.index', compact('categories'));
    }

    // Add karne ke liye
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        // Insert logic using DB Facade
        $id = DB::table('category')->insertGetId([
            'name'       => $request->name,
            'shop_id' => Session::get('shopuser')->id??0,
            'created_at' => now(),
            
        ]);

        $category = DB::table('category')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'data'   => $category
        ]);
    }

    // Delete karne ke liye
    public function destroy(Request $request)
    {
        $deleted = DB::table('category')->where('id', $request->id)->delete();

        if ($deleted) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Kuch gadbad hai!']);
    }

    // Update (Edit) ke liye (Agar baad me chahiye ho)
    public function update(Request $request)
    {
        DB::table('category')
            ->where('id', $request->id)
            ->update([
                'name'       => $request->name,
                
            ]);

        return response()->json(['status' => 'success']);
    }
}