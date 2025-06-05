<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $_title }} @if(!empty($_action)) - @endif {{ $_action }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-xl">
                        <form method="POST" action="{{ route('kembali.save') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="tanggal_kembali" value="Tanggal Kembali *" />
                                <x-text-input id="tanggal_kembali" name="tanggal_kembali" type="text" class="mt-1 block w-full datepicker" :value="old('tanggal_kembali', $field['tanggal_kembali'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_kembali')" />
                            </div>

                            <div>
                                <x-input-label for="anggota" value='Anggota *' />
                                <select class="form-control select2" name="anggota" id="anggota">
                                    <option value="">-- Pilih --</option>
                                    @foreach($listAnggota as $anggota)
                                        <option value="{{ $anggota->id }}" @if((old('anggota', $field['anggota'])) == $anggota->id) selected @endif>{{ $anggota->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('anggota')" />
                            </div>

                            <div>
                                <x-input-label for="anggota" value='Data Pinjaman Buku *' />
                                <x-input-error class="mt-2" :messages="$errors->get('buku')" />
                                <div id="data-pinjam"><i class="text-muted">Pilih anggota terlebih dahulu</i></div>
                            </div>

                            <div>
                                <x-text-input name="id" type="hidden" value="{{ $field['id'] }}" />
                                <x-primary-button>{{ __('Simpan') }}</x-danger-button>
                                <x-secondary-button class="ms-3" onclick="window.location.href='{{ route('kembali.show') }}'">{{ __('Kembali') }}</x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script type="text/javascript">
            let anggotaId = $('#anggota').val();
            getPinjaman(anggotaId);

            $('#anggota').on('change', function() {
                getPinjaman($(this).val());
            });

            async function getPinjaman(anggotaId) {
                let url = {!! json_encode(route('kembali.json.cek.pinjaman', ':id')) !!}.replace(':id', anggotaId);
                
                try {
                    const response = await fetch(url);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const data = await response.json();
                    $('#data-pinjam').html(data);
                } catch (error) {
                    console.error('Error fetching data:', error);
                }
            }
        </script>
    @endsection
</x-app-layout>