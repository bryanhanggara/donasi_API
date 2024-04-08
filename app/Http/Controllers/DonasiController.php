<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;    
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;


class DonasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $donasi = $user->donasis()->latest()->get();

        return response()->json([
            'message' => 'Data berhasil didapatkan',
            'status' => 'success',
            'data' => $donasi
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
                'name' => 'required',
                'desc'=> 'required',
                'cover' => 'required|image|max:2048',
                'terkumpul'=> 'required',
            ]);
        
            $user = Auth::user();
    
            $username = $user->name;

            $item = new Donasi(); 
            $item->name = $request->name;
            $item->nama_user = $username;
            $item->user_id = $user->id;
            $item->desc = $request->desc;
            $item['cover'] = $request->file('cover')
                            ->store('cover', 'public');
            $item->terkumpul = $request->terkumpul;
        
            $item->save();
        
            
            return response()->json([
                'message' => 'berhasil ditambah',
                'data' => [
                    'donasi' => $item,
                    'user' => $item->user
                ]
            ]);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => '',
            'desc'=> '',
            'cover' => 'nullable|image|max:2048',
            'terkumpul'=> '',
        ]);

        $item = Donasi::findOrFail($id);
        $item->name = $request->name;
        $item->desc = $request->desc;
        $item->terkumpul = $request->terkumpul;
    
        if ($request->hasFile('cover')) {
            // Hapus file cover lama jika ada
            Storage::delete($item->cover);
    
            // Simpan file cover baru
            $item->cover = $request->file('cover')->store('cover', 'public');
        }
    
        $item->save();
    
        return response()->json([
            'message' => 'Donasi berhasil diperbarui',
            'data' => $item
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Donasi::findorfail($id);
        $data->delete();
        return response()->json([
            'message' => 'kehapus'
        ]);
    }
}
