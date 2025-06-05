@if($dataPinjaman->isEmpty())
    <h5 class="text-success">Tidak ada data pinjaman buku.</h5>
@else
    @foreach($dataPinjaman as $rows)
        @foreach($rows as $details)
            <label>
                <input type="checkbox" name="buku[]" value="{{ $details->pinjam_id }}|{{ $details->buku_id }}"> &nbsp;
                {{ $details->judul_buku }} 
                (Penerbit: {{ $details->penerbit }}) 
                | Tanggal Pinjam: {{ date('d-m-Y', strtotime($details->tanggal_pinjam)) }}
            </label>
            <br />
        @endforeach
    @endforeach
@endif
