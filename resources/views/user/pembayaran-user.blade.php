@extends('layouts.user')

@section('title', 'Pembayaran')

@section('page_title', 'Pembayaran')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tagihan</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Bayar</th>
                                <th>Batas Pembayaran</th>
                                <th>Status</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments->sortByDesc('tanggal_bayar') as $index => $payment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->tanggal_bayar)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->batas_pembayaran)->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge {{ $payment->status == 'Selesai' ? 'bg-success' : ($payment->status == 'Dalam Proses' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($payment->price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($payment->status == 'Belum Dibayar')
                                        <button class="btn btn-primary" onclick="checkout('{{ $payment->id }}')">Bayar</button>
                                    @elseif ($payment->status == 'Dalam Proses')
                                        <button class="btn btn-warning" onclick="continuePayment('{{ $payment->id }}', '{{ $payment->snap_token }}')">Lanjut Pembayaran</button>
                                    @else
                                        <a href="{{ route('pembayaran.downloadPdf', $payment->id) }}" class="btn btn-danger">Download PDF</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Pembayaran -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailModalLabel">Detail Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentDetailContent">
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="#" id="downloadPdfBtn" class="btn btn-danger">Download PDF</a>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Midtrans -->
<script type="text/javascript" 
    src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ config('midtrans.client_key') }}"
    data-cookie-policy="strict"
    data-cookie-samesite="Lax"
    data-cookie-secure="true"
    data-cookie-partitioned="true">
</script>

<script>
    function showPaymentDetail(paymentId) {
        const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
        const contentDiv = document.getElementById('paymentDetailContent');
        const downloadBtn = document.getElementById('downloadPdfBtn');
        
        // Set download link
        downloadBtn.href = `{{ url('pembayaran/download-pdf') }}/${paymentId}`;
        
        // Fetch payment details
        fetch(`/api/payment-detail/${paymentId}`)
            .then(response => response.json())
            .then(data => {
                contentDiv.innerHTML = `
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>Tanggal Bayar</th>
                                <td>${data.tanggal_bayar}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><span class="badge bg-success">${data.status}</span></td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td>Rp ${data.price}</td>
                            </tr>
                        </table>
                    </div>
                `;
                modal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Gagal memuat detail pembayaran',
                    icon: 'error'
                });
            });
    }

    function checkout(paymentId) {
        // Show loading
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        console.log('Memulai proses checkout untuk payment ID:', paymentId);

        // Lakukan request checkout
        fetch('/api/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                payment_id: paymentId,
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response checkout:', data);

            if (!data.success) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses pembayaran');
            }

            if (!data.snap_token) {
                throw new Error('Snap token tidak ditemukan');
            }

            // Tampilkan popup pembayaran
            Swal.close();
            showPaymentPopup(paymentId, data.snap_token);
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.message || 'Terjadi kesalahan saat memproses pembayaran',
                showConfirmButton: true
            });
        });
    }

    function continuePayment(paymentId, snapToken) {
        if (snapToken) {
            showPaymentPopup(paymentId, snapToken);
        } else {
            showErrorMessage('Token pembayaran tidak valid, silakan mulai pembayaran baru');
        }
    }

    function showPaymentPopup(paymentId, snapToken) {
        if (!snapToken) {
            console.error('Snap token tidak valid');
            showErrorMessage('Token pembayaran tidak valid');
            return;
        }

        console.log('Menampilkan popup pembayaran:', { paymentId, snapToken });
        
        snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('Pembayaran berhasil:', result);
                updatePaymentStatus(paymentId, 'Selesai')
                    .then(() => {
                        showSuccessMessage('Pembayaran berhasil!');
                        setTimeout(() => { window.location.reload(); }, 2000);
                    })
                    .catch(error => {
                        console.error('Error update status:', error);
                        showErrorMessage('Gagal mengupdate status pembayaran');
                    });
            },
            onPending: function(result) {
                console.log('Pembayaran pending:', result);
                updatePaymentStatus(paymentId, 'Dalam Proses')
                    .then(() => {
                        showWarningMessage('Pembayaran sedang diproses');
                        setTimeout(() => { window.location.reload(); }, 2000);
                    })
                    .catch(error => {
                        console.error('Error update status:', error);
                        showErrorMessage('Gagal mengupdate status pembayaran');
                    });
            },
            onError: function(result) {
                console.error('Pembayaran error:', result);
                updatePaymentStatus(paymentId, 'Belum Dibayar')
                    .then(() => {
                        showErrorMessage('Pembayaran gagal: ' + (result.message || 'Terjadi kesalahan'));
                        setTimeout(() => { window.location.reload(); }, 2000);
                    })
                    .catch(error => {
                        console.error('Error update status:', error);
                        showErrorMessage('Gagal mengupdate status pembayaran');
                    });
            },
            onClose: function() {
                console.log('Popup pembayaran ditutup');
                checkPaymentStatus(paymentId)
                    .catch(error => {
                        console.error('Error saat cek status:', error);
                        showWarningMessage('Gagal mengecek status pembayaran');
                    });
            }
        });
    }

    function checkPaymentStatus(paymentId) {
        fetch(`/api/check-payment-status/${paymentId}`, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Status pembayaran:', data);
            if (data.status !== 'Selesai') {
                updatePaymentStatus(paymentId, 'Dalam Proses')
                    .then(() => {
                        showWarningMessage('Pembayaran dapat dilanjutkan nanti');
                        setTimeout(() => { window.location.reload(); }, 2000);
                    })
                    .catch(error => {
                        console.error('Error update status:', error);
                        showErrorMessage('Gagal mengupdate status pembayaran');
                    });
            }
        })
        .catch(error => {
            console.error('Error cek status:', error);
            showErrorMessage('Gagal mengecek status pembayaran');
        });
    }

    function updatePaymentStatus(paymentId, status) {
        return fetch('/api/update-payment-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_id: paymentId,
                status: status,
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        });
    }

    function showSuccessMessage(message) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function showWarningMessage(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: message,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function showErrorMessage(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            showConfirmButton: true
        });
    }
</script>
@endsection
