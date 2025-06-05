<?php

namespace App\Http\Controllers\Trans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\MessageBag;
use App\Models\TransPinjam;
use App\Models\TransPinjamItem;
use App\Models\BaseAnggota;
use App\Models\BaseBuku;

class PinjamController extends Controller
{
    public function __construct()
    {
        $this->title = 'Peminjaman';
        $this->limit = env('PAGE_LIMIT');
    }

    public function show()
    {
        $search = '';
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }

        $listData = TransPinjam::with('anggota')
                        ->whereHas('anggota', function ($query) use ($search) {
                            $query->where('no_anggota', 'like', '%' . $search . '%')
                                ->orWhere('nama', 'like', '%' . $search . '%');
                        })
                        ->orderBy('tanggal_pinjam', 'desc')
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

        return view('trans.pinjamShow', $data);
    }

    public function add()
    {
        $data = [
            '_title' => $this->title,
            '_action' => 'Pinjam Buku',
            'listAnggota' => BaseAnggota::getComboAnggota(),
            'listBuku' => BaseBuku::getComboBuku()->pluck('name', 'id'),
            'field' => [
                'act' => 'add',
                'tanggal_pinjam' => date('d/m/Y'),
                'anggota' => '',
                'listBuku' => '',
                'countBuku' => 0,
                'id' => ''
            ],
        ];

        return view('trans.pinjamForm', $data);
    }

    public function update($id)
    {
        $detail = TransPinjam::where('id', $id)->first();
        if(empty($detail)){
            return abort(404);
        }
        
        $items = TransPinjamItem::where('pinjam_id', $detail->id)->get();
        $data = [
            '_title' => $this->title,
            '_action' => 'Edit',
            'listAnggota' => BaseAnggota::getComboAnggota(),
            'listBuku' => BaseBuku::getComboBuku()->pluck('name', 'id'),
            'field' => [
                'act' => 'update',
                'tanggal_pinjam' => date('d/m/Y', strtotime($detail->tanggal_pinjam)),
                'anggota' => $detail->anggota_id,
                'listBuku' => $items,
                'countBuku' => $items->count(),
                'id' => $detail->id
            ],
        ];

        return view('trans.pinjamForm', $data);
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
        $validator = Validator::make($request->all(), [
            'tanggal_pinjam' => ['required','date_format:d/m/Y'],
            'anggota' => ['required'],
        ]);
        $validator->setAttributeNames([
            'tanggal_pinjam' => 'tanggal pinjam',
            'anggota' => 'anggota',
        ]);

        $errors = new MessageBag();
        if ($validator->fails()) {
            $errors = $validator->errors();
        } 

        if(!isset($request->buku)){
            $errors->add('buku',  __('validation.required', ['attribute' => 'buku']));
        } else {
            if(is_array($request->buku)){
                foreach($request->buku as $keys => $rows)
                {
                    if(empty($request->buku[$keys])){
                        $errors->add('buku',  __('validation.required', ['attribute' => 'buku']));
                    } 
                }
            }
        }

        if (!$errors->isEmpty()) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try{
            DB::beginTransaction();

            $data = new TransPinjam();
            $data->tanggal_pinjam = Carbon::createFromFormat('d/m/Y', $request->tanggal_pinjam)->format('Y-m-d');
            $data->anggota_id = $request->anggota;
            $data->save();

            if(isset($request->buku)){
                $paramBuku = [];
                if(is_array($request->buku)){
                    foreach($request->buku as $keys => $rows){
                        if(!empty($request->buku[$keys])){
                            $paramJadwal[] = [
                                'id' => Str::uuid()->toString(),
                                'pinjam_id' => $data->id,
                                'buku_id' => $request->buku[$keys],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }
                    }
                    TransPinjamItem::insert($paramJadwal);
                }
            }

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('pinjam.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function saveUpdate($request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal_pinjam' => ['required','date_format:d/m/Y'],
            'anggota' => ['required'],
        ]);
        $validator->setAttributeNames([
            'tanggal_pinjam' => 'tanggal pinjam',
            'anggota' => 'anggota',
        ]);

        $errors = new MessageBag();
        if ($validator->fails()) {
            $errors = $validator->errors();
        } 

        if(!isset($request->buku)){
            $errors->add('buku',  __('validation.required', ['attribute' => 'buku']));
        } else {
            if(is_array($request->buku)){
                foreach($request->buku as $keys => $rows)
                {
                    if(empty($request->buku[$keys])){
                        $errors->add('buku',  __('validation.required', ['attribute' => 'buku']));
                    } 
                }
            }
        }

        if (!$errors->isEmpty()) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        try{
            DB::beginTransaction();

            $data = TransPinjam::where('id', $request->id)->first();
            if(empty($data)){
                return abort(404);
            }

            $data->tanggal_pinjam = Carbon::createFromFormat('d/m/Y', $request->tanggal_pinjam)->format('Y-m-d');
            $data->anggota_id = $request->anggota;
            $data->save();

            TransPinjamItem::where('pinjam_id', $request->id)->delete();
            if(isset($request->buku)){
                $paramBuku = [];
                if(is_array($request->buku)){
                    foreach($request->buku as $keys => $rows){
                        if(!empty($request->buku[$keys])){
                            $paramJadwal[] = [
                                'id' => Str::uuid()->toString(),
                                'pinjam_id' => $data->id,
                                'buku_id' => $request->buku[$keys],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }
                    }
                    TransPinjamItem::insert($paramJadwal);
                }
            }

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('pinjam.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }

    function delete(Request $request): RedirectResponse
    {
        try{
            DB::beginTransaction();

            $detail = TransPinjam::where('id', $request->id)->first();
            if(empty($detail)){
                return abort(404);
            }
            
            TransPinjamItem::where('pinjam_id', $request->id)->delete();
            $detail->delete();

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.delete_success');
        } catch (\Exception $e) {
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.delete_error');
        }

        return redirect::route('pinjam.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }
}
