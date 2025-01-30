@extends('layouts.mainlayout')

@section('title', 'Home')

@push('styles')
@endpush

@section('content')

<!-- Page header -->
<div class="page-header">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4 class="font-weight-semibold"><i class="icon-arrow-left52 mr-2"></i> Dashboard</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
    </div>
</div>
<!-- /page header -->

<div class="containe px-4">
    <div class="row justify-content-center gy-2">
        <div class="col-12">
            <div class="card ">
                <div class="card-header font-weight-bold h4">CLOSING DAILY RATES</div>
                <div class="card-body py-3 pb-3">
                    <div class="row gy-4">
                        <div class="col-12 col-md-6 col-lg-3">
                            <label>CURRENCY:</label>
                            <select disabled id="currencyDropdown" class="form-control form-control-sm font-weight-bold">
                                <option value="">loading..</option>
                            </select>
                        </div>
                        <!-- <div class="col-md-6 col-lg-2">
                            <label>Select Year:</label>
                            <input type="text" id="yearpicker" class="datepicker form-control">
                        </div> -->

                        <div class="col-12 col-md-6 col-lg-2">
                            <label>FROM:</label>
                            <input disabled type="text" id="startpicker" class="datepicker form-control form-control-sm font-weight-bold">
                        </div>

                        <div class="col-12 col-md-6 col-lg-2 mb-3">
                            <label>TO:</label>
                            <input disabled type="text" id="endpicker" class="datepicker form-control form-control-sm font-weight-bold">
                        </div>

                        <div class="col-12 col-md-6 col-lg-2 mb-3">
                            <label>&nbsp;</label>
                            <input id="searchBtn" onclick="getClosingDailyRates()" value="SEARCH" type="button" class="form-control btn btn-dark form-control-sm">
                        </div>

                        <div class="col-md-12">
                            <div class="card" id="chartReport">
                                <div class="card-body" id="chart">
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>

</div>
<!-- Content area -->
<div class="content pt-0">

</div>
<!-- /content area -->
@endsection

@push('scripts')


<script  >
    // utility function to return  date range from one point to another
    function dateRange(startDate, endDate, steps = 1) {
        const dateArray = [];
        let currentDate = new Date(startDate);

        while (currentDate <= new Date(endDate)) {
            dateArray.push(new Date(currentDate));
            // Use UTC date to prevent problems with time zones and DST
            currentDate.setUTCDate(currentDate.getUTCDate() + steps);
        }

        return dateArray.map(
            (x) => `${x.getFullYear()}/${x.getMonth() + 1}/${x.getDate()}`
        );
    }

    // set start date to last n days
    // var start_date = new Date(new Date().getFullYear(), 0, 1); //from jan of current yr
    var setStartDate = new Date();
    setStartDate.setDate(setStartDate.getDate() - 30);
    var start_date = setStartDate;

    var end_date = new Date();
    var closingRates = [];
    let currencyList = [];

    document.addEventListener('DOMContentLoaded', function() {
        getCurrencies();
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: `2017:${(new Date()).getFullYear()}`,
            maxDate: new Date()
        });

        // set date picker default dates
        $("#startpicker").datepicker("setDate", start_date);
        $("#endpicker").datepicker("setDate", end_date);
        $('#searchBtn').prop('disabled', true);

    });


    // get currencies from API
    function getCurrencies() {
        $.ajax({
            method: 'GET',
            dataType: "json",
            url: '/currencies',
            success: function(data, status, xhr) {
                currencyList = data;
                // console.log(data);
                loadCurrenciesOnSelectBox()

            },
            error: function(xhr) {
                console.log(xhr.responseJSON.message);
            }
        });
    }


    //load the currencies on dropdown
    function loadCurrenciesOnSelectBox() {
        var select = document.getElementById("currencyDropdown");

        // Optional: Clear all existing options first:
        select.innerHTML = "";
        // Populate list with options:
        for (var i = 0; i < currencyList.length; i++) {
            var opt = currencyList[i];
            select.innerHTML += "<option value=\"" + opt.id + "\">" + opt.currency + "</option>";
        }

        // set to dollar
        let dollarID = currencyList.length ? currencyList.find(x => x.currency_code == 'USD').id : '';
        $('#currencyDropdown').val(dollarID)

        $('#currencyDropdown').prop('disabled', false);
        $('#startpicker').prop('disabled', false);
        $('#endpicker').prop('disabled', false);
        $('#searchBtn').prop('disabled', false);
        getClosingDailyRates()
    }


    // get rates from API
    function getClosingDailyRates() {

        let queryData = {
            currency_id: $('#currencyDropdown').val(),
            start_date: $('#startpicker').val(),
            end_date: $('#endpicker').val(),
        }

        console.log('quering with: ', queryData);

        $('#searchBtn').prop('disabled', true);
        $('#searchBtn').val('SEARCHING...');

        $.ajax({
            method: 'GET',
            data: queryData,
            dataType: "json",
            url: '/closing_daily_rates',
            success: function(data, status, xhr) {
                $('#searchBtn').prop('disabled', false);
                $('#searchBtn').val('SEARCH');
                console.log('got this: ', data);
                if (data.length == 0) {
                    let selectedCurrency = $("#currencyDropdown option:selected").text();
                    $("#chartReport").html(`<div class="text-center" style="padding-block:100px; font-size:16px" id="chart">No data to show for selected currency: (<span class="font-weight-bold">${selectedCurrency}</span>) <br> or <b>date range.</b> </div>`)
                    return;
                }
                closingRates = data
                closingRates.forEach(element => {
                    element.trade_date = new Date(element.trade_date).toLocaleDateString()
                });
                prepareGraphData()
            },
            error: function(xhr) {
                $('#searchBtn').prop('disabled', false);
                $('#searchBtn').val('SEARCH');
                console.log(xhr.responseJSON.message);
            }
        });
    }

    // prepare data for chart
    function prepareGraphData() {
        // merge rates of more than one date together
        let mapped = closingRates.map(n => ({
            Date: new Date(n.trade_date).toLocaleDateString(),
            Rate: parseInt(n.fx_rate)
        }))

        // get range from dates
        const range = dateRange($('#startpicker').val(), $('#endpicker').val());
        // console.log('current Range: ', range);

        let chartArray = [];
        var currRate = mapped[0].Rate;
        range.forEach((currDate) => {
            var obj = {};
            obj.Date = currDate;
            obj.Rate = currRate;
            mapped.forEach((currObj) => {
                if (new Date(currDate).toLocaleDateString() == currObj.Date) {
                    currRate = obj.Rate = currObj.Rate;
                }
            });
            chartArray.push(obj);
        });

        loadGraph(chartArray)
    }

    // utility array fot month string
    const monstr = (num) => {
        if (num == "01" || num == "1")
            return "Jan";
        if (num == "02" || num == "2")
            return "Feb";
        if (num == "03" || num == "3")
            return "Mar";
        if (num == "04" || num == "4")
            return "Apr";
        if (num == "05" || num == "5")
            return "May";
        if (num == "06" || num == "6")
            return "Jun";
        if (num == "07" || num == "7")
            return "Jul";
        if (num == "08" || num == "8")
            return "Aug";
        if (num == "09" || num == "9")
            return "Sep";
        if (num == "10")
            return "Oct";
        if (num == "11")
            return "Nov";
        if (num == "12")
            return "Dec";
    };


    // load the chart
    function loadGraph(graphData) {
        let prices = graphData.map(x => x.Rate)
        let dates = graphData.map(
            x => `${new Date(x.Date).getDate()}/${monstr(new Date(x.Date).getMonth()+1)}/${new Date(x.Date).getFullYear().toString().substr(-2)}`
        )

        console.log('plotting: ', dates);
        console.log('against: ', prices);

        var options = {
            series: [{
                name: "Rate",
                text: "Rates",
                data: prices,
                color: "#000000",
            }, ],
            chart: {
                height: 400,
                type: "area",
                // stacked: false,
                zoom: {
                    enabled: false,
                },
            },
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 0,
            },
            stroke: {
                // curve: ['smooth', 'straight', 'stepline'],
                show: true,
                curve: 'smooth',
                lineCap: 'round', //butt, square
                // colors: undefined,
                width: 1,
                dashArray: 0,
            },

            // title: {
            //     text: '',
            //     align: 'left'
            // },
            fill: {
                type: "gradient",
                gradient: {
                    // shade: 'light',
                    gradientToColors: ['#00FF00'],
                    shadeIntensity: 0,
                    inverseColors: true,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 75],
                },
            },
            grid: {
                row: {
                    colors: ['#f8f8f8', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            yaxis: {
                title: {
                    text: 'Rates'
                },
                // min: 5,
                // max: 40
            },
            xaxis: {
                categories: dates,
                type: "datetime",
                labels: {
                    datetimeFormatter: {
                        year: 'yyyy',
                        month: 'MMM \'yyyy',
                        day: 'dd MMM',
                        hour: 'HH:mm'
                    }
                }
            },
        };
        document.querySelector("#chartReport").innerHTML = '<div id="chart"></div>';
        // var chart = new ApexCharts(document.querySelector("#chart"), options);
        // chart.destroy();
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    }
</script>

<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> -->
<link rel="stylesheet" href="/resources/demos/style.css">
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


@endpush