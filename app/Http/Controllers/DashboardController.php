<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaseBuku;
use App\Models\TransPinjamItem;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function show()
    {
        $data = [];
        return view('dashboard', $data);
    }

    function chartJson()
    {
    
        $dataLabel = [];
        $dataTotal = [];

        $listBuku = BaseBuku::get();
        $list = TransPinjamItem::select('buku_id', DB::raw('count(*) as total'))
                    ->leftJoin('trans_pinjams', 'pinjam_id', 'trans_pinjams.id')
                    ->leftJoin('base_bukus', 'buku_id', 'base_bukus.id')
                    ->whereBetween('tanggal_pinjam', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ])
                    ->groupBy('buku_id')
                    ->orderBy('total', 'desc')
                    ->get();

        if(!empty($listBuku)){
            foreach($listBuku as $rows){
                $tempTotal = 0;

                if(!empty($list)){
                    foreach($list as $vals){
                        if($vals->buku_id == $rows->id){
                            $tempTotal = $vals->total;
                        }
                    }
                }

                $dataLabel[] = $rows->judul_buku. ', Penerbit:  '. $rows-> penerbit;
                $dataTotal[] = $tempTotal;
            }
        }

        $labels = $dataLabel;
        $datasets = [
            [
                "data" => $dataTotal
            ]
        ];

        return response()->json([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }
}
