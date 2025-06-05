<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BaseAnggota;

class AnggotaController extends Controller
{
    public function __construct()
    {
        $this->title = 'Master Anggota';
        $this->limit = env('PAGE_LIMIT');
    }

    public function show()
    {
        $search = '';
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }

        $listData = BaseAnggota::where(function ($query) use ($search) {
                                        $query->where('no_anggota', 'like', '%'.$search.'%')
                                        ->orWhere('nama', 'like', '%'.$search.'%');
                                    })
                        ->orderBy('nama', 'asc')
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

        return view('base.anggotaShow', $data);
    }

    public function add()
    {
        $data = [
            '_title' => $this->title,
            '_action' => 'Tambah',
            'field' => [
                'act' => 'add',
                'no_anggota' => '',
                'nama' => '',
                'tanggal_lahir' => '',
                'id' => ''
            ],
        ];

        return view('base.anggotaForm', $data);
    }

    public function update($id)
    {
        $detail = BaseAnggota::where('id', $id)->first();
        if(empty($detail)){
            return abort(404);
        }
        
        $data = [
            '_title' => $this->title,
            '_action' => 'Edit',
            'field' => [
                'act' => 'update',
                'no_anggota' => $detail->no_anggota,
                'nama' => $detail->nama,
                'tanggal_lahir' => date('d-m-Y', strtotime($detail->tanggal_lahir)),
                'id' => $detail->id
            ],
        ];

        return view('base.anggotaForm', $data);
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
            'no_anggota' => ['required', 'unique:base_anggotas,no_anggota'],
            'nama' => ['required'],
            'tanggal_lahir' => ['required','date_format:d/m/Y'],
        ], [], [
            'no_anggota' => 'no anggota',
            'nama' => 'nama',
            'tanggal_lahir' => 'tanggal lahir',
        ]);

        try{
            DB::beginTransaction();

            $data = new BaseAnggota();
            $data->no_anggota = $request->no_anggota;
            $data->nama = $request->nama;
            $data->tanggal_lahir = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
            $data->save();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('anggota.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function saveUpdate($request): RedirectResponse
    {
        $request->validate([
            'no_anggota' => ['required', 'unique:base_anggotas,no_anggota,'.$request->id],
            'nama' => ['required'],
            'tanggal_lahir' => ['required','date_format:d/m/Y'],
        ], [], [
            'no_anggota' => 'no anggota',
            'nama' => 'nama',
            'tanggal_lahir' => 'tanggal lahir',
        ]);

        try{
            DB::beginTransaction();

            $data = BaseAnggota::where('id', $request->id)->first();
            if(empty($data)){
                return abort(404);
            }

            $data->no_anggota = $request->no_anggota;
            $data->nama = $request->nama;
            $data->tanggal_lahir = Carbon::createFromFormat('d/m/Y', $request->tanggal_lahir)->format('Y-m-d');
            $data->save();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('anggota.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function delete(Request $request): RedirectResponse
    {
        try{
            DB::beginTransaction();

            $detail = BaseAnggota::where('id', $request->id)->first();
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

        return redirect::route('anggota.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }
}
