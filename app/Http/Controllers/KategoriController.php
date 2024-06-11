<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Storage;

//import query builder
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $akategori = array(
            'M' => 'Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        );

         //return $rsetKategori;
         if ($request->search){
            //query builder
            $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('getKategori(kategori) as kat'))
                                                 ->where('id','like','%'.$request->search.'%')
                                                 ->orWhere('deskripsi','like','%'.$request->search.'%')
                                                 ->orWhere('kategori','like','%'.$request->search.'%')
                                                ->paginate(10);
           
        }else {
            $rsetKategori = DB::table('kategori')->select('id','deskripsi',DB::raw('getKategori(kategori) as kat'))->paginate(10);
        }

        return view('v_kategori.index', compact('rsetKategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $akategori = array(
            'blank' => 'Pilih Kategori',
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        );
        return view('v_kategori.create', compact('akategori'));
    }


   

    //validate form
    public function store(Request $request)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required',
        ]);

    // //cek data
    // echo "data deskripsi";
    // echo $request->deskripsi;
    // die('asd');

        // Create a new Kategori
        Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        // Redirect to index
        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);
        return view('v_kategori.show', compact('rsetKategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $akategori = array(
            'blank' => 'Pilih Kategori',
            'M' => 'Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        );

        $rsetKategori = Kategori::find($id);
        return view('v_kategori.edit', compact('rsetKategori', 'akategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required',
        ]);

        $rsetKategori = Kategori::find($id);
        $rsetKategori->update($request->all());

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (DB::table('barang')->where('kategori_id', $id)->exists()){
            return redirect()->route('kategori.index')->with(['Gagal' => 'Gagal dihapus']);
        } else {
            $rseKategori = Kategori::find($id);
            $rseKategori->delete();
            return redirect()->route('kategori.index')->with(['Success' => 'Berhasil dihapus']);
        }
    }



//API
    //Menampilkan semua kategori
    function getAPIKategori(){
        $kategori = Kategori::all();
        $data = array("data"=>$kategori);

        return response()->json($data);
    }
    
    //Buat kategori baru
    function createAPIKategori(Request $request)
    {
        // Validasi data yang diterima dari request
        $validatedData = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'kategori' => 'required|string|max:3'
        ]);

        //BERBASIS ELOQUENT
        // Buat kategori baru menggunakan data yang sudah divalidasi
        $kategori = Kategori::create([
            'deskripsi' => $validatedData['deskripsi'],
            'kategori' => $validatedData['kategori']
        ]);

        // Mengembalikan respons JSON dengan data kategori yang baru dibuat
        return response()->json([
            'data' => [
                'id' => $kategori->id,
                'created_at' => $kategori->created_at,
                'updated_at' => $kategori->updated_at,
                'deskripsi' => $kategori->deskripsi,
                'kategori' => $kategori->kategori
            ]
        ], 200); // Status 200 Created
    }

    //Update salah satu kategori
   function updateAPIKategori(Request $request, string $id) {
    $kategori = Kategori::find($id);
    if (!$kategori) {
        return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
    }


    $kategori->deskripsi=$request->deskripsi;
    $kategori->kategori=$request->kategori;
    $kategori->save();


    return response()->json(['status' => 'Kategori berhasil diubah'], 200);          
}

//Show atau Menampilkan Salah Satu Kategori
    function showAPIKategori(string $id){
        $kategori = Kategori::find($id);
        $data = array("data"=>$kategori);

        return response()->json($data);
}

//Delete salah satu kategori
    function deleteAPIKategori(string $id)
{
    if (DB::table('barang')->where('kategori_id', $id)->exists()){
        // Menambahkan return response dengan status 500
        return response()->json(['error' => 'kategori tidak dapat dihapus'], 500);
    } else {
        $rseKategori = Kategori::find($id);
        if ($rseKategori) {
            $rseKategori->delete();
            return response()->json(['success' => 'Berhasil dihapus'], 200);
        } else {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }
    }
}





}