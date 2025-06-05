@props(['url'])

@if($url)
    <div class="modal fade show" id="DeleteModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-info modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5><i class="icon fas fa-info-circle fa-lg mr-2"></i> Hapus</h5>
                    <h6 class="mt-4">Data yang dihapus tidak bisa dikembalikan! Apakah Anda yakin?</h6>
                </div>
                <form method="post" action="{{ $url }}">
                    @csrf
                    <div class="modal-footer">
                        <input type="hidden" id="DeleteId" name="id" />
                        <button type="reset" class="btn btn-dark btn-sm" data-dismiss="modal"><i class="fas fa-arrow-alt-circle-left mr-1"></i> Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm me-4"><i class="fas fa-trash mr-1"></i> Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif