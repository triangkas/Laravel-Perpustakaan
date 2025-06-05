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
                        <x-secondary-button onclick="window.location.href='{{ route('anggota.add') }}'">
                            {{ __('Tambah') }}
                        </x-secondary-button>

                        <form method="GET" action="{{ route('anggota.show') }}" class="ml-auto">
                            <x-text-input name="search" type="text" :placeholder="__('Pencarian')" :value="$field['search']" />
                        </form>
                    </div>

                    <x-alert-session-messages type="{{ session('statusType') }}" messages="{{ session('statusMessage') }}" />
                    
                    <table width="100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>No.</th>
                                <th>No. Anggota</th>
                                <th>Nama</th>
                                <th>Tanggal Lahir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$listData->isEmpty())
                                @foreach($listData as $key => $rows)
                                    <tr>
                                        <td align="center" width="250">
                                            <x-secondary-button class="ms-3" onclick="window.location.href='{{ route('anggota.update', $rows->id) }}'">{{ __('Edit') }}</x-danger-button>
                                            <x-secondary-button class="ms-3" onclick="confirmDelete('{{ $rows->id }}')">{{ __('Hapus') }}</x-danger-button>
                                        </td>
                                        <td align="center">{{ ($listData->firstItem() + $key) }}</td>
                                        <td align="center">{{ $rows->no_anggota }}</td>
                                        <td align="left">{{ $rows->nama }}</td>
                                        <td align="center">{{ date('d-m-Y', strtotime($rows->tanggal_lahir)) }}</td>
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

    @section('js')
        <script type="text/javascript">
            function confirmDelete(id){
                $('#DeleteModal').modal('toggle');
                $('#DeleteId').val(id)[0].dispatchEvent(new Event('input')); 
                return false;
            }
        </script>
    @endsection
    <x-modal-delete url="{{ route('anggota.delete') }}" />
</x-app-layout>