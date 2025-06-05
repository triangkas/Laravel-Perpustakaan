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
                        <form method="POST" action="{{ route('buku.save') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <x-input-label for="judul" value="Judul Buku *" />
                                <x-text-input id="judul" name="judul" type="text" class="mt-1 block w-full" :value="old('judul', $field['judul'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('judul')" />
                            </div>

                            <div>
                                <x-input-label for="penerbit" value="Penerbit *" />
                                <x-text-input id="penerbit" name="penerbit" type="text" class="mt-1 block w-full" :value="old('penerbit', $field['penerbit'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('penerbit')" />
                            </div>

                            <div>
                                <x-input-label for="dimensi" value="Dimensi *" />
                                <x-text-input id="dimensi" name="dimensi" type="text" class="mt-1 block w-full" :value="old('dimensi', $field['dimensi'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('dimensi')" />
                            </div>

                            <div>
                                <x-input-label for="stock" value="Stock *" />
                                <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full" :value="old('stock', $field['stock'])" />
                                <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                            </div>

                            <div>
                                <x-text-input name="id" type="hidden" value="{{ $field['id'] }}" />
                                <x-primary-button>{{ __('Simpan') }}</x-danger-button>
                                <x-secondary-button class="ms-3" onclick="window.location.href='{{ route('buku.show') }}'">{{ __('Kembali') }}</x-danger-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>