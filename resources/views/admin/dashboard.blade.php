@extends('layouts.app')

@section('title', 'Dashboard - PENA')

@section('content')
<div class="space-y-6">
    <!-- Header Component -->
    <x-dashboard-header 
        title="Dashboard Statistik & Rekapitulasi" 
        subtitle="PENA • Pelayanan dan Administrasi ASN" 
    />

    <!-- Stats Cards Component -->
    <x-dashboard-stats 
        :today="$todayCount" 
        :month="$monthCount" 
        :year="$yearCount" 
        :selesai="$selesaiCount" 
        :diproses="$diprosesCount" 
        :menunggu="$menungguCount" 
    />

    <!-- Charts & Lists Component -->
    <x-dashboard-charts 
        :kecamatanChart="$kecamatanChart" 
        :sekolahChart="$sekolahChart" 
    />

    <!-- Recent Tables Component -->
    <x-dashboard-tables 
        :recentLayanan="$recentLayanan" 
    />
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Layanan Chart Data
        const layananLabels = {!! json_encode($layananChart->pluck('layanan')) !!};
        const layananData = {!! json_encode($layananChart->pluck('total')) !!};

        new Chart(document.getElementById('layananChartCanvas'), {
            type: 'doughnut',
            data: {
                labels: layananLabels,
                datasets: [{
                    data: layananData,
                    backgroundColor: [
                        '#0ea5e9', // sky-500
                        '#38bdf8', // sky-400
                        '#0284c7', // sky-600
                        '#075985', // sky-800
                        '#7dd3fc', // sky-300
                        '#bae6fd', // sky-200
                        '#e0f2fe'  // sky-100
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 9,
                                weight: '600'
                            },
                            color: '#475569',
                            boxWidth: 8,
                            padding: 12
                        }
                    }
                },
                cutout: '70%'
            }
        });

        // Bulan Chart Data
        const bulanLabels = {!! json_encode($bulanChart->pluck('bulan')) !!};
        const bulanData = {!! json_encode($bulanChart->pluck('total')) !!};

        new Chart(document.getElementById('bulanChartCanvas'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets: [{
                    label: 'Jumlah Layanan',
                    data: bulanData,
                    backgroundColor: '#0ea5e9',
                    borderRadius: 6,
                    borderWidth: 0,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            precision: 0,
                            font: { size: 9, weight: '600' },
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 9, weight: '600' },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
