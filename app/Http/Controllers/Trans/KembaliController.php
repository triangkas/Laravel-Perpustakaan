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
use App\Models\TransKembali;
use App\Models\TranskembaliItem;
use App\Models\BaseAnggota;
use App\Models\BaseBuku;

class KembaliController extends Controller
{
    public function __construct()
    {
        $this->title = 'Pengembalian';
        $this->limit = env('PAGE_LIMIT');
    }

    public function show()
    {
        $search = '';
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }

        $listData = TransKembali::with('pinjam.anggota')
                        ->whereHas('pinjam.anggota', function ($query) use ($search) {
                            $query->where('no_anggota', 'like', '%' . $search . '%')
                                ->orWhere('nama', 'like', '%' . $search . '%');
                        })
                        ->orderBy('tanggal_kembali', 'desc')
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

        return view('trans.kembaliShow', $data);
    }

    public function jsonCekPinjaman($anggotaId) 
    {
        $dataPinjaman = TransPinjam::query()
                        ->where('trans_pinjams.anggota_id', $anggotaId)
                        ->join('trans_pinjam_items as tpi', 'trans_pinjams.id', '=', 'tpi.pinjam_id')
                        ->join('base_bukus as b', 'b.id', '=', 'tpi.buku_id')
                        ->whereNotExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from('trans_kembalis as tk')
                                ->join('trans_kembali_items as tki', 'tki.kembali_id', '=', 'tk.id')
                                ->whereColumn('tk.pinjam_id', 'trans_pinjams.id')
                                ->whereColumn('tki.buku_id', 'tpi.buku_id');
                        })
                        ->select(
                            'trans_pinjams.id as pinjam_id',
                            'trans_pinjams.tanggal_pinjam as tanggal_pinjam',
                            'trans_pinjams.anggota_id',
                            'tpi.buku_id',
                            'b.judul_buku as judul_buku',
                            'b.penerbit as penerbit'
                        )
                        ->orderBy('trans_pinjams.tanggal_pinjam')
                        ->get()
                        ->groupBy('pinjam_id');

        // dd($dataPinjaman);
        $data = [
            'dataPinjaman' => $dataPinjaman
        ];

        return response()->json(view('trans.dataPinjam', $data)->render());
    }

    public function add()
    {
        $data = [
            '_title' => $this->title. ' Buku',
            '_action' => '',
            'listAnggota' => BaseAnggota::getComboAnggota(),
            'field' => [
                'act' => 'add',
                'tanggal_kembali' => date('d/m/Y'),
                'anggota' => '',
                'id' => ''
            ],
        ];

        return view('trans.kembaliForm', $data);
    }

    public function save(Request $request): RedirectResponse
    {
        return $this->saveAdd($request);
    }

    function saveAdd($request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kembali' => ['required','date_format:d/m/Y'],
            'anggota' => ['required'],
        ]);
        $validator->setAttributeNames([
            'tanggal_kembali' => 'tanggal kembali',
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

            if(isset($request->buku)){
                $paramKembali = [];
                $paramKembaliItem = [];
                if(is_array($request->buku)){
                    $tempPinjamId = '';
                    foreach($request->buku as $rows){
                        [$pinjam_id, $buku_id] = explode('|', $rows);
                    
                        // untuk trans kembali
                        if($pinjam_id != $tempPinjamId){
                            $id_kembali = Str::uuid()->toString();
                            $paramKembali[] = [
                                'id' => $id_kembali,
                                'pinjam_id' => $pinjam_id,
                                'tanggal_kembali' => Carbon::createFromFormat('d/m/Y', $request->tanggal_kembali)->format('Y-m-d'),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now()
                            ];
                        }

                        // untuk trans kembali item
                        $paramKembaliItem[] = [
                            'id' => Str::uuid()->toString(),
                            'kembali_id' => $id_kembali,
                            'buku_id' => $buku_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                
                        $tempPinjamId = $pinjam_id;
                    }
                    TransKembali::insert($paramKembali);
                    TransKembaliItem::insert($paramKembaliItem);
                }

            }

            DB::commit();
            $statusType = 'success';
            $statusMessage = trans('messages.save_success');
        } catch (\Exception $e) {

            dd($e);
            DB::rollback();
            $statusType = 'error';
            $statusMessage = trans('messages.save_error');
        }

        return redirect::route('kembali.show')->with(['statusType' => $statusType, 'statusMessage' => $statusMessage]);
    }
}
