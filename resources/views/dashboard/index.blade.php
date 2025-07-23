@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>

    </ul>
@endsection

@push('script-page')
    <script>
        var options = {
            series: [{
                name: "{{__('Payment')}}",
                type: 'column',
                data: {!! json_encode($result['paymentOverview']['payment']) !!},
            }],
            chart: {
                height: 452,
                type: 'line',
                toolbar:{
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            legend:{
                show:false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [0,0],
                curve: 'smooth',
            },
            plotOptions: {
                bar: {
                    columnWidth:"20%",
                    startingShape:"rounded",
                    endingShape: "rounded",
                }
            },
            fill:{
                opacity:[1, 0.08],
                gradient:{
                    type:"horizontal",
                    opacityFrom:0.5,
                    opacityTo:0.1,
                    stops: [100, 100, 100]
                }
            },
            colors: [Codexdmeki.themeprimary],
            states: {
                normal: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                hover: {
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'darken',
                        value: 1,
                    }
                },
            },
            grid:{
                strokeDashArray: 2,
            },

            yaxis:{
                tickAmount: 10 ,
                labels:{
                    formatter: function (y) {
                        return  "{{$result['settings']['CURRENCY_SYMBOL']}}" + y.toFixed(0);
                    },
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },
            },
            xaxis: {
                categories: {!! json_encode($result['paymentOverview']['label']) !!} ,
                axisTicks: {
                    show:false
                },
                axisBorder:{
                    show:false
                },
                labels:{
                    style: {
                        colors: '#262626',
                        fontSize: '14px',
                        fontWeight: 500,
                        fontFamily: 'Roboto, sans-serif'
                    },
                },
            },
            responsive:[
                {
                    breakpoint: 1441,
                    options:{
                        chart:{
                            height: 445
                        }
                    },
                },
                {
                    breakpoint: 1366,
                    options:{
                        chart:{
                            height: 320
                        }
                    },
                },
            ]
        };
        var chart = new ApexCharts(document.querySelector("#paymentOverview"), options);
        chart.render();
    </script>
@endpush
@php
$settings=settings();
@endphp
@section('content')
<div class="row">
    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #ecf0f1; color: #34495e; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#95a5a6'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#ecf0f1'; this.style.color='#34495e'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Claim Intimated')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Claim Intimated']}}</span></h2>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #fef5e7; color: #e67e22; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#e67e22'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#fef5e7'; this.style.color='#e67e22'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Documents Pending')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Documents Pending']}}</span></h2>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #e8f8f5; color: #1abc9c; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#1abc9c'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#e8f8f5'; this.style.color='#1abc9c'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Documents Submitted')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Documents Submitted']}}</span></h2>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #f5eef8; color: #9b59b6; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#9b59b6'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#f5eef8'; this.style.color='#9b59b6'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Under Review')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Under Review']}}</span></h2>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #fdecea; color: #e74c3c; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#e74c3c'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#fdecea'; this.style.color='#e74c3c'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Rejected')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Rejected']}}</span></h2>
            </div>
        </div>
    </div>

    <div class="col-xxl-3 col-sm-6 cdx-xxl-50">
        <div class="card sale-revenue" style="background-color: #eafaf1; color: #2ecc71; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#2ecc71'; this.style.color='#ffffff'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.05)';" onmouseout="this.style.backgroundColor='#eafaf1'; this.style.color='#2ecc71'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Approved')}}</h4>
            </div>
            <div class="card-body progressCounter">
                <h2><span class="count">{{$result['Approved']}}</span></h2>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-12 cdx-xxl-50">
        <div class="card overall-revenuetbl" style="background-color: #f8f9fa; color: #34495e; border: 1px solid #ddd; border-radius: 8px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='#e3eaf3'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'; this.style.transform='scale(1.02)';" onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.boxShadow='none'; this.style.transform='scale(1)';">
            <div class="card-header">
                <h4>{{__('Payment Overview')}}</h4>
            </div>
            <div class="card-body">
                <div id="paymentOverview"></div>
            </div>
        </div>
    </div>
</div>
@endsection
