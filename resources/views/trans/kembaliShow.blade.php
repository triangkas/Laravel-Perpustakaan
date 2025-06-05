<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $_title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center w-full">
                        <x-secondary-button onclick="window.location.href='{{ route('kembali.add') }}'">
                            {{ __('Pengembalian Buku') }}
                        </x-secondary-button>

                        <form method="GET" action="{{ route('kembali.show') }}" class="ml-auto">
                            <x-text-input name="search" type="text" :placeholder="__('Pencarian')" :value="$field['search']" />
                        </form>
                    </div>

                    <x-alert-session-messages type="{{ session('statusType') }}" messages="{{ session('statusMessage') }}" />
                    
                    <table width="100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>No. Anggota</th>
                                <th>Nama Anggota</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Buku</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$listData->isEmpty())
                                @foreach($listData as $key => $rows)
                                    <tr>
                                        <td align="center">{{ ($listData->firstItem() + $key) }}</td>
                                        <td align="center">{{ $rows->pinjam->anggota->no_anggota }}</td>
                                        <td align="left">{{ $rows->pinjam->anggota->nama }}</td>
                                        <td align="center">{{ date('d-m-Y', strtotime($rows->pinjam->tanggal_pinjam)) }}</td>
                                        <td align="center">{{ date('d-m-Y', strtotime($rows->tanggal_kembali)) }}</td>
                                        <td align="left">
                                            @if(!empty($rows->kembaliItem))
                                                @foreach($rows->kembaliItem as $item)
                                                   - {{ $item->buku->judul_buku }} (Penerbit: {{ $item->buku->penerbit }}) <br />
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="100%" align="center">Data not found</td></tr>
                            @endif
                        </tbody>
                    </table>
                    {{ $listData->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>