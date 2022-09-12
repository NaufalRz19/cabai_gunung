@extends('admin.layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card" style="background-color: #B9E4C9;">
                <div class="card-header">
                    <h4 style="color: #356859;">{{ strtoupper(auth()->user()->status) }} {{ auth()->user()->name }}</h4>
                </div>
                <div class="card-body" style="color: #356859;">
                    {{ auth()->user()->address }} <br> {{ auth()->user()->phone_number }}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 style="color: #FD5523;">Rekapitulasi Harian</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1" style="background-color: #B9E4C9;">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 style="color: #356859;">Hasil Cabai Sehat (Kg)</h4>
                    </div>
                    <div class="card-body" style="color: #356859;">
                        {{ $cabai_sehat }}
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1" style="background-color: #B9E4C9;">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 style="color: #356859;">Hasil Cabai Rusak (Kg)</h4>
                    </div>
                    <div class="card-body" style="color: #356859;">
                        {{ $cabai_rusak }}
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1" style="background-color: #B9E4C9;">
                <div class="card-wrap">
                    <div class="card-header">
                        <h4 style="color: #356859;">Laba(Rp)</h4>
                    </div>
                    <div class="card-body" style="color: #356859;">
                        {{ 'Rp. '.number_format($laba) }}
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 style="color: #FD5523;">Rekapitulasi Bulanan</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="color: #356859">Hasil Cabai Sehat (Kg)</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart" height="182"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="color: #356859">Hasil Cabai Rusak (Kg)</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart2" height="182"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="color: #356859">Hasil Laba Bulanan</h4>
                </div>
                <div class="card-body">
                    <canvas id="myChart3" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- <script src="assets/js/page/index.js"></script> --}}
    <script>
        "use strict";

        var ctx = document.getElementById("myChart").getContext('2d');
        let dataSehat = @json($sehat_tahun);
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                datasets: [{
                        label: 'Cabai Sehat',
                        data: dataSehat,
                        borderWidth: 2,
                        backgroundColor: 'rgba(63,82,227,.8)',
                        borderWidth: 0,
                        borderColor: 'transparent',
                        pointBorderWidth: 0,
                        pointRadius: 3.5,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
                    },
                ]
            },
            options: {
                legend: {
                    display: true
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            // display: false,
                            drawBorder: true,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                            callback: function(value, index, values) {
                                return value;
                            }
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: true,
                            tickMarkLength: 15,
                        }
                    }]
                },
            }
        });
        var ctx = document.getElementById("myChart2").getContext('2d');
        let dataRusak = @json($rusak_tahun);
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                datasets: [{
                        label: 'Cabai Rusak',
                        data: dataRusak,
                        borderWidth: 2,
                        backgroundColor: 'rgba(63,82,227,.8)',
                        borderWidth: 0,
                        borderColor: 'transparent',
                        pointBorderWidth: 0,
                        pointRadius: 3.5,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
                    },
                ]
            },
            options: {
                legend: {
                    display: true
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            // display: false,
                            drawBorder: true,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                            callback: function(value, index, values) {
                                return value;
                            }
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: true,
                            tickMarkLength: 15,
                        }
                    }]
                },
            }
        });
        var ctx = document.getElementById("myChart3").getContext('2d');
        let dataLaba = @json($laba_tahun);
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Januari", "Febuari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
                datasets: [{
                        label: 'Laba Bulanan',
                        data: dataLaba,
                        borderWidth: 2,
                        backgroundColor: 'rgba(63,82,227,.8)',
                        borderWidth: 0,
                        borderColor: 'transparent',
                        pointBorderWidth: 0,
                        pointRadius: 3.5,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
                    },
                ]
            },
            options: {
                legend: {
                    display: true
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            // display: false,
                            drawBorder: true,
                            color: '#f2f2f2',
                        },
                        ticks: {
                            beginAtZero: true,
                            // stepSize: 5,
                            callback: function(value, index, values) {
                                return value;
                            }
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: true,
                            // tickMarkLength: 15,
                        }
                    }]
                },
            }
        });
    </script>
@endsection
