@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-red text-center">
                <div class="inner">
                    <h3 id="totalPengeluaran">{{ $initialData['totalPengeluaran'] }}</h3>
                    <p class="fs-4">Pengeluaran</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-green text-center">
                <div class="inner">
                    <h3 id="totalPemasukan">{{$initialData['totalPemasukan']}}</h3>
                    <p class="fs-4">Pemasukan</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-blue text-center">
                <div class="inner">
                <h3 id="totalPenghasilan">{{$initialData['totalPenghasilan']}}</h3>
                    <p class="fs-4">Penghasilan</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-yellow text-center">
                <div class="inner">
                    <h3 id="totalIndekos">{{ $initialData['totalIndekos'] }}</h3>
                    <p class="fs-4">Total Indekos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="timeRange">Pilih Rentang Waktu:</label>
        <select id="timeRange" class="form-control">
            <option value="day" {{ $initialData['timeRange'] == 'day' ? 'selected' : '' }}>Per Hari</option>
            <option value="month" {{ $initialData['timeRange'] == 'month' ? 'selected' : '' }}>Per Bulan</option>
            <option value="year" {{ $initialData['timeRange'] == 'year' ? 'selected' : '' }}>Per Tahun</option>
        </select>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="text-center">
                <canvas id="indekosChart" class="img-fluid"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let indekosChart;

        // Inisialisasi data awal
        let initialData = @json($initialData);
        console.log("Data Awal:", initialData); // Cek data di console

        function createChart(labels, dataPengeluaran, dataPemasukan, dataPenghasilan, timeUnit) {
            const ctx = document.getElementById('indekosChart').getContext('2d');

            if (indekosChart) {
                indekosChart.destroy();
            }

            indekosChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Pengeluaran',
                        data: dataPengeluaran,
                        borderColor: 'rgb(255, 2, 57)',
                        backgroundColor: 'rgba(253, 149, 172, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }, {
                        label: 'Total Pemasukan',
                        data: dataPemasukan,
                        borderColor: 'rgba(7, 222, 93, 0.96)',
                        backgroundColor: 'rgba(114, 255, 182, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }, {
                        label: 'Total Penghasilan',
                        data: dataPenghasilan,
                        borderColor: 'rgb(0, 97, 254)',
                        backgroundColor: 'rgba(164, 234, 255, 0.2)',
                        borderWidth: 2,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: timeUnit,
                                tooltipFormat: getTooltipFormat(timeUnit),
                                displayFormats: getDisplayFormats(timeUnit),
                            },
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (Rp)'
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Grafik Pemasukan, Pengeluaran, dan Penghasilan'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        },
                        legend: {
                            position: 'bottom', // Perbaiki legend
                        }
                    }
                }
            });
        }

        function getTooltipFormat(unit) {
            switch (unit) {
                case 'month':
                    return 'MMM yyyy'; // Perbaiki format bulan
                case 'year':
                    return 'yyyy';
                default:
                    return 'dd MMM yyyy'; // Perbaiki format hari
            }
        }

        function getDisplayFormats(unit) {
            switch (unit) {
                case 'month':
                    return { month: 'MMM yyyy' }; // Perbaiki format bulan
                case 'year':
                    return { year: 'yyyy' };
                default:
                    return { day: 'dd MMM' };
            }
        }

        // Buat chart awal
        createChart(initialData.allDates, initialData.dataPengeluaran, initialData.dataPemasukan, initialData.dataPenghasilan, initialData.timeRange);

        // Event listener untuk perubahan rentang waktu
        document.getElementById('timeRange').addEventListener('change', function() {
            const selectedValue = this.value;

            fetch(`/admin/dashboard?timeRange=${selectedValue}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log("Data dari server:", data); // Cek data dari server

                document.getElementById('totalPengeluaran').textContent = data.totalPengeluaran.toLocaleString('id-ID');
                document.getElementById('totalPemasukan').textContent = data.totalPemasukan.toLocaleString('id-ID');
                document.getElementById('totalPenghasilan').textContent = data.totalPenghasilan.toLocaleString('id-ID');
                document.getElementById('totalIndekos').textContent = data.totalIndekos;
                createChart(data.allDates, data.dataPengeluaran, data.dataPemasukan, data.dataPenghasilan, selectedValue);
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
@endsection