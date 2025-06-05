<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $_title }} - {{ $_action }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="max-w-xl">
                        <form method="POST" action="{{ route('pinjam.save') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="tanggal_pinjam" value="Tanggal Pinjam *" />
                                <x-text-input id="tanggal_pinjam" name="tanggal_pinjam" type="text" class="mt-1 block w-full datepicker" :value="old('tanggal_pinjam', $field['tanggal_pinjam'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_pinjam')" />
                            </div>

                            <div>
                                <x-input-label for="anggota" value='Anggota *' />
                                <select class="form-control select2" name="anggota">
                                    <option value="">-- Pilih --</option>
                                    @foreach($listAnggota as $anggota)
                                        <option value="{{ $anggota->id }}" @if((old('anggota', $field['anggota'])) == $anggota->id) selected @endif>{{ $anggota->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('anggota')" />
                            </div>

                            <div>
                                <x-input-label for="buku" value="Buku *" />
                                <x-input-error class="mt-2" :messages="$errors->get('buku')" />

                                <table id="tableBuku">
                                    <tbody>
                                        @if(!empty($field['countBuku']))
                                            @foreach($field['listBuku'] as $key => $rows)
                                                @php($number = $key+1)
                                                <tr id="bukuRow_{{$number}}" class="bukuRow">
                                                    <td width="30" style="border: 0"><button class="btn btn-danger btn-sm" type="button" title="Hapus" onclick="removeRowBuku('{{$number}}'); return false;">Hapus</button></td>
                                                    <td style="border: 0">
                                                        <select class="form-control select2" name="buku[]">
                                                            <option value="">-- Pilih --</option>
                                                            @foreach($listBuku as $id => $name)
                                                                <option value="{{ $id }}" @if($id == $rows->buku_id) selected @endif>{{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>

                                <button type="button" class="btn btn-success btn-sm ml-2" id="btnAddBuku">Tambah Buku</button>
                            </div>

                            <div>
                                <x-text-input name="id" type="hidden" value="{{ $field['id'] }}" />
                                <x-primary-button>{{ __('Simpan') }}</x-danger-button>
                                <x-secondary-button class="ms-3" onclick="window.location.href='{{ route('pinjam.show') }}'">{{ __('Kembali') }}</x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            let listBuku = @json($listBuku);
            let totalBuku = "{{ $field['countBuku'] }}";
            if(totalBuku == 0){
                addRowBuku(1);
            }

            $("#btnAddBuku").click(function(event){
                event.preventDefault();
                let totalBuku = $(".bukuRow").length;
                cekBukuRow(totalBuku);
            });

            function cekBukuRow(number){
                if($("#bukuRow_"+number).length){
                    number++;
                    cekBukuRow(number);
                } else {
                    addRowBuku(number);
                }
            }

            function addRowBuku(number) {
                let form = '<tr id="bukuRow_'+number+'" class="bukuRow">';
                form += '<td width="30" style="border: 0"><button class="btn btn-danger btn-sm" type="button" title="Hapus" onclick="removeRowBuku('+number+'); return false;">Hapus</button></td>';

                let select = '<select class="form-control select2" name="buku[]"><option value="">-- Pilih --</option>';
                for (const [id, name] of Object.entries(listBuku)) {
                    select += `<option value="${id}">${name}</option>`;
                }
                select += '</select>';
                form += '<td style="border: 0">' + select + '</td>';
                form += '</tr>';
                $("#tableBuku tbody").append(form);
                $('.select2').select2();
            }

            function removeRowBuku(number) {
                $('#bukuRow_'+number).remove();
            }
        </script>
    @endsection
</x-app-layout>