
 
       
       
       function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        $(document).ready(function() {
            $('#harga').on('keyup', function() {
                $(this).val(formatRupiah(this.value));
            });

            $('#fasilitas_id').select2({
                tags: true,
                placeholder: 'Pilih atau ketik fasilitas',
                allowClear: true,
                ajax: {
                    url: "{{ route('get-category') }}",
                    type: "POST",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            name: params.term,
                            "_token": "{{ csrf_token() }}",
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.nama_fasilitas
                                };
                            })
                        };
                    },
                }
            });

            // Initialize Select2 for edit modal
            $('#edit_fasilitas_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'Pilih fasilitas',
                allowClear: true
            });

            // Handle edit button click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const noKamar = $(this).data('no_kamar');
                const harga = $(this).data('harga');
                let fasilitas = $(this).data('fasilitas');
                const status = $(this).data('status');

                // Pastikan fasilitas adalah array
                if (typeof fasilitas === 'string') {
                    fasilitas = fasilitas.split(',');
                } else if (typeof fasilitas === 'number') {
                    fasilitas = [fasilitas.toString()]; // Jika fasilitas adalah angka, ubah menjadi array dengan satu elemen
                } else {
                    fasilitas = []; // Jika bukan string atau angka, inisialisasi sebagai array kosong
                }

                $('#edit_no_kamar').val(noKamar);
                $('#edit_harga').val(harga);
                $('#edit_fasilitas_id').val(fasilitas).trigger('change');
                $('#edit_status').val(status);

                // Ambil nilai indekosId dari elemen HTML dengan id 'indekos-id'
                const indekosId = $('#indekos-id').data('indekos-id'); 

                // Gunakan indekosId untuk membentuk URL action
                $('#editKamarForm').attr('action', `/indekos/${indekosId}/kamar/${id}`);
                $('#editKamarModal').modal('show');
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const hasErrors = document.body.dataset.errors === 'true';
            if (hasErrors) {
                var tambahKamarModal = new bootstrap.Modal(document.getElementById('tambahKamarModal'));
                tambahKamarModal.show();
            }
        });
