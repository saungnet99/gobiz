@extends('admin.layouts.index', ['header' => true, 'nav' => true, 'demo' => true])

{{-- Custom CSS --}}
@section('css')
<style>
    [data-bs-theme="dark"] text {
        fill: rgb(250, 250, 250);
    }

    [data-bs-theme="light"] text {
        fill: rgb(0, 0, 0);
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="container-xl">
        <!-- Page title -->
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h2 class="page-title">
                        {{ __('Dashboard') }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            {{-- Message --}}
            @if(session()->has('message'))
                <div class="alert alert-important alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                          <!-- Download SVG icon from http://tabler-icons.io/i/info-circle -->
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path><path d="M12 9h.01"></path><path d="M11 12h1v4h1"></path></svg>
                        </div>
                        <div>
                            {!! session('message') !!}
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @php
                    session()->forget('message');
                @endphp
            @endif

            {{-- Failed --}}
            @if(Session::has("failed"))
            <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('failed')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            {{-- Success --}}
            @if(Session::has("success"))
            <div class="alert alert-important alert-success alert-dismissible mb-2" role="alert">
                <div class="d-flex">
                    <div>
                        {{Session::get('success')}}
                    </div>
                </div>
                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
            @endif

            <div class="row row-deck row-cards mb-5">
                {{-- This Month Income --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader">{{ __('This Month Income') }}</div>
                            </div>
                            <div class="h1">{{ $currency->symbol }}{{ number_format($thisMonthIncome, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Today Income --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader">{{ __('Today Income') }}</div>
                            </div>
                            <div class="h1">{{ $currency->symbol }}{{ number_format($today_income, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Overall Users --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader">{{ __('Overall Users') }}</div>
                            </div>
                            <div class="h1">{{ $overall_users }}</div>
                        </div>
                    </div>
                </div>

                {{-- Today User --}}
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader">{{ __('Today Users') }}</div>
                            </div>
                            <div class="h1">{{ $today_users }}</div>
                        </div>
                    </div>
                </div>



                {{-- Total Earnings, vCard creation and Store creation --}}
                <div class="col-md-12 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader mb-2">{{ __('Overview') }}</div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div id="overview"></div>
                                </div>
                                <div class="col-md-auto">
                                    <div class="divide-y divide-y-fill">
                                        <div class="px-3">
                                            <div>
                                                <span class="status-dot bg-red"></span> {{ __('Earnings') }}
                                            </div>
                                            <div class="h2">{{ $currency->symbol }}{{ number_format($totalEarnings, 2)
                                                }}</div>
                                        </div>
                                        <div class="px-3">
                                            <div>
                                                <span class="status-dot bg-orange"></span> {{ __('vCards') }}
                                            </div>
                                            <div class="h2">{{ $totalvCards }}</div>
                                        </div>
                                        <div class="px-3">
                                            <div>
                                                <span class="status-dot bg-green"></span> {{ __('Stores') }}
                                            </div>
                                            <div class="h2">{{ $totalStores }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Current Week Sales --}}
                <div class="col-md-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            {{-- Title --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader mb-2">{{ __('Current Week Sales') }}</div>
                            </div>
                            <div id="current-week-sales"></div>
                        </div>
                    </div>
                </div>



                {{-- Sales Overwise --}}
                <div class="col-md-12 col-lg-12 col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            {{-- Title --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader mb-2">{{ __('Sales Overview') }}</div>
                            </div>
                            {{-- Chart --}}
                            <div id="sales" class="chart-sm">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Users Overwise --}}
                <div class="col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            {{-- Title --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="subheader mb-2">{{ __('Users Overview') }}</div>
                            </div>
                            {{-- Chart --}}
                            <div id="users" class="chart-sm">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @include('admin.includes.footer')
</div>

{{-- Custom JS --}}
@section('scripts')
<script src="{{ asset('js/apexcharts.min.js') }}"></script>
<script>
    "use strict";

    // Sales Overview
    document.addEventListener("DOMContentLoaded", function () {
        "use strict";
        window.ApexCharts && (new ApexCharts(document.getElementById('sales'), {
            chart: {
                type: "area",
                fontFamily: 'inherit',
                height: 310,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: false
                },
                stacked: true,
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: .16,
                type: 'solid'
            },
            stroke: {
                width: 2,
                lineCap: "round",
                curve: "smooth",
            },
            series: [{
                name: "{{ __('Sales') }}",
                data: [{{ $monthIncome }}]
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                categories: [`{{ __('Jan') }}`, `{{ __('Feb') }}`, `{{ __('Mar') }}`, `{{ __('Apr') }}`, `{{ __('May') }}`, `{{ __('Jun') }}`, `{{ __('July') }}`, `{{ __('Aug') }}`, `{{ __('Sept') }}`, `{{ __('Oct') }}`, `{{ __('Nov') }}`, `{{ __('Dec') }}`],
            },
            yaxis: {
                labels: {
                    padding: 4
                },
            },
            colors: ['#035AC4'],
            legend: {
                show: false,
            },
        })).render();
    });

    // Users overview
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('users'), {
            chart: {
                type: "bar",
                fontFamily: 'inherit',
                height: 310,
                parentHeightOffset: 0,
                toolbar: {
                    show: false,
                },
                animations: {
                    enabled: false
                },
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false,
            },
            fill: {
                opacity: 1,
            },
            series: [{
                name: `{{ __('New Users') }}`,
                data: [{{ $monthUsers }}]
            }],
            tooltip: {
                theme: 'dark'
            },
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4,
            },
            xaxis: {
                labels: {
                    padding: 0,
                },
                tooltip: {
                    enabled: false
                },
                axisBorder: {
                    show: false,
                },
                type: 'month',
            },
            yaxis: {
                labels: {
                    padding: 4
                },
                max: 20,
            },
            labels: [`{{ __('Jan') }}`, `{{ __('Feb') }}`, `{{ __('Mar') }}`, `{{ __('Apr') }}`, `{{ __('May') }}`, `{{ __('Jun') }}`, `{{ __('July') }}`, `{{ __('Aug') }}`, `{{ __('Sept') }}`, `{{ __('Oct') }}`, `{{ __('Nov') }}`, `{{ __('Dec') }}`],
            colors: ['#035AC4'],
            legend: {
                show: false,
            },
        })).render();
    });
    // @formatter:on

    // Overview
    // @formatter:off
	document.addEventListener("DOMContentLoaded", function () {
		window.ApexCharts && (new ApexCharts(document.getElementById('overview'), {
			chart: {
				type: "line",
				fontFamily: 'inherit',
				height: 310,
				parentHeightOffset: 0,
				toolbar: {
					show: false,
				},
				animations: {
					enabled: false
				},
			},
			fill: {
				opacity: 1,
			},
			stroke: {
				width: 2,
				lineCap: "round",
				curve: "smooth",
			},
			series: [{
				name: `{{ __("Earning") }}`,
				data: [{{ $earnings }}]
			},{
				name: `{{ __("vCards") }}`,
				data: [{{ $vcards }}]
			},{
				name: `{{ __("Stores") }}`,
				data: [{{ $stores }}]
			}],
			tooltip: {
				theme: 'dark'
			},
			grid: {
				padding: {
					top: -20,
					right: 0,
					left: -4,
					bottom: -4
				},
				strokeDashArray: 4,
			},
			xaxis: {
				labels: {
					padding: 0,
				},
				tooltip: {
					enabled: false
				},
				type: 'year',
			},
			yaxis: {
				labels: {
					padding: 4,
				},
			},
			labels: [`{{ __('Jan') }}`, `{{ __('Feb') }}`, `{{ __('Mar') }}`, `{{ __('Apr') }}`, `{{ __('May') }}`, `{{ __('Jun') }}`, `{{ __('July') }}`, `{{ __('Aug') }}`, `{{ __('Sept') }}`, `{{ __('Oct') }}`, `{{ __('Nov') }}`, `{{ __('Dec') }}`],
			colors: ["#D63939", "#F76707", "#2FB344"],
			legend: {
				show: false,
			},
		})).render();
	});
	// @formatter:on

    // Current week sales
    // @formatter:off
    var currentWeekSalesLabel = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday ', 'Sunday'];
    if ({{ array_sum($currentWeekSales['sum']) }} <= 0) {
        currentWeekSalesLabel = [""];
    }
    document.addEventListener("DOMContentLoaded", function () {
      	window.ApexCharts && (new ApexCharts(document.getElementById('current-week-sales'), {
      		chart: {
      			type: "donut",
      			fontFamily: 'inherit',
      			height: 310,
      			sparkline: {
      				enabled: true
      			},
      			animations: {
      				enabled: false
      			},
      		},
      		fill: {
      			opacity: 1,
      		},
      		series: {!! array_sum($currentWeekSales['sum']) <= 0 ? "[100]" : json_encode($currentWeekSales['sum']) !!},
      		labels: currentWeekSalesLabel,
      		tooltip: {
      			theme: 'dark'
      		},
      		grid: {
      			strokeDashArray: 4,
      		},
      		colors: ["#4263eb", "#f76707", "#330902", "#1092a3", "#ed023d", "#007a0c", "#db25db"],
      		legend: {
      			show: true,
      			position: 'bottom',
      			offsetY: 12,
      			markers: {
      				width: 10,
      				height: 10,
      				radius: 100,
      			},
      			itemMargin: {
      				horizontal: 8,
      				vertical: 8
      			},
      		},
      		tooltip: {
      			fillSeriesColor: false
      		},
      	})).render();
      });
      // @formatter:on
</script>
@endsection
@endsection