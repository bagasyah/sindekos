<!DOCTYPE html>
<html>
<head>
    <style>
       body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <div class="header">Halo, {{ $userName }}</div>
            <p class="line">Pembayaran baru telah dibuat untuk Anda.</p>
            <p class="line">Jumlah: <strong>{{ $price }}</strong></p>
            <p class="line">Tanggal Pembayaran: <strong>{{ $paymentDate }}</strong></p>
            <p class="line">Batas Pembayaran: <strong>{{ $dueDate }}</strong></p>
        </div>
    </div>
</body>
</html>
