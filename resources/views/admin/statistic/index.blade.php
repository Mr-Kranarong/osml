@extends('layouts.app')

@section('content')

@auth
{{-- ========================================== ADMIN ========================================== --}}
@if (Auth::user()->hasAccess())
<div class="container">
    <div class="row justify-content-center">
        {{-- stat --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{__('text.Statistics')}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <canvas id="profitGraph">

                            </canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <canvas id="viewsGraph">

                            </canvas>
                        </div>
                        <div class="col-lg-4">
                            <canvas id="wishlistsGraph">

                            </canvas>
                        </div>
                        <div class="col-lg-4">
                            <canvas id="salesGraph">

                            </canvas>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <canvas id="categoriesGraph">

                            </canvas>
                        </div>
                        <div class="col-lg-6">
                            <canvas id="stocksGraph">

                            </canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ========================================== ADMIN ========================================== --}}
@endauth

@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" integrity="sha512-d9xgZrVZpmmQlfonhQUvTR7lMPtO7NkZMkA0ABN3PHCbKA5nqylQ/yWlFAyY6hYgdF1Qh6nYiuADWwKB4C2WSw==" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        renderGraphs();
    });

    function renderGraphs(){
        // Profit
        $.post('{{route('statistic.profit')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.MonthlyProfit')}}',
                    backgroundColor: '#00000050',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#profitGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'line',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });

        // View
        $.post('{{route('statistic.views')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.TopViews')}}',
                    backgroundColor: '#00000000',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#viewsGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'radar',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });

        // Wishlist
        $.post('{{route('statistic.wishlists')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.TopWishlists')}}',
                    backgroundColor: '#00000000',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#wishlistsGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'radar',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });

        // Sales
        $.post('{{route('statistic.sales')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.TopSales')}}',
                    backgroundColor: '#00000000',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#salesGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'radar',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });

        // Categories
        $.post('{{route('statistic.categories')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.ProductEachCategories')}}',
                    backgroundColor: '#00000050',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#categoriesGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'polarArea',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });

        // Stocks
        $.post('{{route('statistic.stocks')}}',function(data){
            let dataarr = JSON.parse(data);
            let inputLabels = [];
            let inputValues = [];

            dataarr.forEach((item) => {
                inputLabels.push(item.Label);
                inputValues.push(item.Value);
            });

            let chartdata = {
                labels: inputLabels,
                datasets: [{
                    label: '{{__('text.LowStocks')}}',
                    backgroundColor: '#00000050',
                    borderColor: '#000000',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: inputValues
                }]
            };

            let graphTarget = $('#stocksGraph');
            let profitGraph = new Chart(graphTarget,{
                   type: 'bar',
                   data: chartdata,
                   options: {
                        responsive: true,
                    }
            });
        });
    }
</script>
@endsection
