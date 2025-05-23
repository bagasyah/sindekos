<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran #{{ $payment->order_id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 20px; position: relative; }
        .receipt { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 5px; position: relative; z-index: 1; background-color: white; }
        .receipt-header, .receipt-footer { text-align: center; margin-bottom: 20px; }
        .receipt-header h2, .receipt-header p { margin: 0; }
        .receipt-details { margin-bottom: 20px; }
        .receipt-details p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            pointer-events: none;
            background-image: url('{{ 'liluk.png' }}');
            background-size: 200px 200px; /* Adjust size as needed */
            background-repeat: repeat;
            opacity: 0.1;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2>Indekos {{ $payment->user->kamar->indekos->nama ?? 'Tidak Diketahui' }}</h2>
            <p>{{ $payment->user->kamar->indekos->alamat ?? 'Alamat Tidak Diketahui' }}</p>
            <p><strong>Tanggal:</strong> {{ $payment->updated_at->format('d-m-Y') }}</p>
        </div>
        <div class="receipt-details">
            <p><strong>Nama Penyewa:</strong> {{ $payment->user->name }}</p>
            <p><strong>No Kamar:</strong> {{ $payment->user->kamar->no_kamar ?? 'Tidak Diketahui' }}</p>
        </div>
        <table>
            <tr>
                <th>Order ID</th>
                <td>{{ $payment->order_id }}</td>
            </tr>
            <tr>
                <th>Tanggal Bayar</th>
                <td>{{ $payment->tanggal_bayar }}</td>
            </tr>
            <tr>
                <th>Batas Pembayaran</th>
                <td>{{ $payment->batas_pembayaran }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $payment->status }}</td>
            </tr>
            <tr>
                <th>Harga</th>
                <td>{{ number_format($payment->price, 2, ',', '.') }}</td>
            </tr>
        </table>
        <div class="receipt-footer">
            <p>Terima kasih telah melakukan pembayaran.</p>
        </div>
    </div>
    <div class="watermark"></div>
</body>
</html>
