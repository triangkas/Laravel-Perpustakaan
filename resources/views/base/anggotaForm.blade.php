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
                        <form method="POST" action="{{ route('anggota.save') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="no_anggota" value="No. Anggota *" />
                                <x-text-input id="no_anggota" name="no_anggota" type="text" class="mt-1 block w-full" :value="old('no_anggota', $field['no_anggota'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('no_anggota')" />
                            </div>

                            <div>
                                <x-input-label for="nama" value="Nama *" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $field['nama'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                            </div>

                            <div>
                                <x-input-label for="tanggal_lahir" value="Tanggal Lahir *" />
                                <x-text-input id="tanggal_lahir" name="tanggal_lahir" type="text" class="mt-1 block w-full datepicker" :value="old('tanggal_lahir', $field['tanggal_lahir'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
                            </div>

                            <div>
                                <x-text-input name="id" type="hidden" value="{{ $field['id'] }}" />
                                <x-primary-button>{{ __('Simpan') }}</x-danger-button>
                                <x-secondary-button class="ms-3" onclick="window.location.href='{{ route('anggota.show') }}'">{{ __('Kembali') }}</x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>