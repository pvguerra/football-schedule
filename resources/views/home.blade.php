@extends('adminlte::page')

@section('title', 'Futebol na TV')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Usuários Ativos</span>
                    <span class="info-box-number">{{$usersActive}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Usuários</span>
                    <span class="info-box-number">{{$usersAll}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-ban"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Usuários Perdidos</span>
                    <span class="info-box-number">{{$usersAll-$usersActive}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{\Carbon\Carbon::now()->format('d/m/Y')}}</span>
                    <span class="info-box-number">
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <!-- charts -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Representação dos usuários</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartUsersActive" width="400" height="400"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Inscrições por mês</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartUsersMonth" width="400" height="400"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
    </div>
@stop
@section('js')
    <script>
        $(function () {
            var usersActive = {!!$usersActive!!};
            var usersAll = {!! $usersAll !!};
            var usersDrop = {!!$usersAll-$usersActive!!};

            var ctx = document.getElementById("chartUsersActive").getContext('2d');
            var chartUsersActive = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ["Usuários Ativos", "Já usaram o bot", "Usuários Perdidos"],
                    datasets: [{
                        backgroundColor: ["#3cba9f","#3e95cd", "#ef1624"],
                        data: [usersActive,usersAll,usersDrop]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });

            var labelMeses = {!! json_encode($labelMeses) !!}
            var dadosMeses = {!! json_encode($dadosMeses) !!}
            console.log(labelMeses);
            var cty = document.getElementById("chartUsersMonth").getContext('2d');
            var chartUsersMonth = new Chart(cty, {
                type: 'bar',
                data: {
                    labels: labelMeses,
                    datasets: [
                        {
                            label: "Agrupamento de inscritos ativos por mês",
                            backgroundColor: [
                                "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9",
                                "#c45850","#3e95cd", "#8e5ea2","#3cba9f",
                                "#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9",
                            ],
                            data: dadosMeses
                        },
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes:
                            [{ticks: {beginAtZero: true}}]
                    }
                }
            });
        });
    </script>
@endsection
