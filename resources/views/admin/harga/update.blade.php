<div class="modal fade" id="modal-edit-{{ $harga->id }}" tabindex="-1" role="dialog"
    aria-labelledby="modalEditLabel{{ $harga->id }}" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ url('admin/harga/' . $harga->id) }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf
                @method('put')

                <!-- Modal Header dengan tombol X -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel{{ $harga->id }}">Edit Harga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Informasi Harga</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="nama_harga">Nama Harga</label>
                                <input type="text" class="form-control" id="nama_harga" name="nama_harga"
                                    style="text-transform: uppercase;" placeholder="masukkan harga"
                                    value="{{ old('nama_harga', $harga->nama_harga) }}">
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Harga</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="harga_a">Harga A</label>
                                <input type="text" class="form-control" id="harga_a" name="harga_a"
                                    placeholder="masukkan harga"
                                    value="{{ old('harga_a', number_format($harga->harga_a, 0, ',', '.')) }}"
                                    oninput="formatRupiah(this)"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group">
                                <label for="harga_b">Harga B</label>
                                <input type="text" class="form-control" id="harga_b" name="harga_b"
                                    placeholder="masukkan harga"
                                    value="{{ old('harga_b', number_format($harga->harga_b, 0, ',', '.')) }}"
                                    oninput="formatRupiah(this)"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group">
                                <label for="harga_c">Harga C</label>
                                <input type="text" class="form-control" id="harga_c" name="harga_c"
                                    placeholder="masukkan harga"
                                    value="{{ old('harga_c', number_format($harga->harga_c, 0, ',', '.')) }}"
                                    oninput="formatRupiah(this)"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group">
                                <label for="harga_d">Harga D</label>
                                <input type="text" class="form-control" id="harga_d" name="harga_d"
                                    placeholder="masukkan harga"
                                    value="{{ old('harga_d', number_format($harga->harga_d, 0, ',', '.')) }}"
                                    oninput="formatRupiah(this)"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>
                            <div class="form-group">
                                <label for="harga_e">Harga E</label>
                                <input type="text" class="form-control" id="harga_e" name="harga_e"
                                    placeholder="masukkan harga"
                                    value="{{ old('harga_e', number_format($harga->harga_e, 0, ',', '.')) }}"
                                    oninput="formatRupiah(this)"
                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                            </div>

                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukan keterangan">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function formatRupiah(input) {
                // Hapus karakter selain angka
                var value = input.value.replace(/\D/g, "");

                // Format angka dengan menambahkan titik sebagai pemisah ribuan
                value = new Intl.NumberFormat('id-ID').format(value);

                // Tampilkan nilai yang sudah diformat ke dalam input
                input.value = value;
            }
        </script>
    </div>
</div>
