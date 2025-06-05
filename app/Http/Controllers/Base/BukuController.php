<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BaseBuku;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->title = 'Master Buku';
        $this->limit = env('PAGE_LIMIT');
    }

    public function show()
    {
        $search = '';
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }

        $listData = BaseBuku::where(function ($query) use ($search) {
                                        $query->where('judul_buku', 'like', '%'.$search.'%')
                                        ->orWhere('penerbit', 'like', '%'.$search.'%');
                                    })
                        ->orderBy('judul_buku', 'asc')
                        ->paginate($this->limit)
                        ->onEachSide(1)
                        ->withQueryString();

        $data = [
            '_title' => $this->title,
            'listData' => $listData,
            'field' => [
                'search' => $search
            ]
        ];

        return view('base.bukuShow', $data);
    }

    public function add()
    {
        $data = [
            '_title' => $this->title,
            '_action' => 'Tambah',
            'field' => [
                'act' => 'add',
                'judul' => '',
                'penerbit' => '',
                'dimensi' => '',
                'stock' => '',
                'id' => ''
            ],
        ];

        return view('base.bukuForm', $data);
    }

    public function update($id)
    {
        $detail = BaseBuku::where('id', $id)->first();
        if(empty($detail)){
            return abort(404);
        }
        
        $data = [
            '_title' => $this->title,
            '_action' => 'Edit',
            'field' => [
                'act' => 'update',
                'judul' => $detail->judul_buku,
                'penerbit' => $detail->penerbit,
                'dimensi' => $detail->dimensi,
                'stock' => $detail->stock,
                'id' => $detail->id
            ],
        ];

        return view('base.bukuForm', $data);
    }

    public function save(Request $request): RedirectResponse
    {
        if(!empty($request->id)){
            return $this->saveUpdate($request);
        } else {
            return $this->saveAdd($request);
        }
    }

    function saveAdd($request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required'],
            'penerbit' => ['required'],
            'dimensi' => ['required'],
            'stock' => ['required','integer'],
        ], [], [
            'judul' => 'judul buku',
            'penerbit' => 'penerbit',
            'dimensi' => 'dimensi',
            'stock' => 'stock',
        ]);

        try{
            DB::beginTransaction();

            $data = new BaseBuku();
            $data->judul_buku = $request->judul;
            $data->penerbit = $request->penerbit;
            $data->dimensi = $request->dimensi;
            $data->stock = $request->stock;
            $data->save();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('buku.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function saveUpdate($request): RedirectResponse
    {
        $request->validate([
            'judul' => ['required'],
            'penerbit' => ['required'],
            'dimensi' => ['required'],
            'stock' => ['required','integer'],
        ], [], [
            'judul' => 'judul buku',
            'penerbit' => 'penerbit',
            'dimensi' => 'dimensi',
            'stock' => 'stock',
        ]);

        try{
            DB::beginTransaction();

            $data = BaseBuku::where('id', $request->id)->first();
            if(empty($data)){
                return abort(404);
            }

            $data->judul_buku = $request->judul;
            $data->penerbit = $request->penerbit;
            $data->dimensi = $request->dimensi;
            $data->stock = $request->stock;
            $data->save();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('buku.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function delete(Request $request): RedirectResponse
    {
        try{
            DB::beginTransaction();

            $detail = BaseBuku::where('id', $request->id)->first();
            if(empty($detail)){
                return abort(404);
            }
            
            $detail->delete();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.delete_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.delete_error');
        }

        return redirect::route('buku.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }
}
