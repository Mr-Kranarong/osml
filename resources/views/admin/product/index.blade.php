@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row">
                    <div class="col-5">
                        {{__('text.TotalProduct')}} ({{$total}})
                    </div>
                    <div class="col-7">
                        <form action="{{route('product.search')}}" method="POST">
                            @csrf
                            <div class="input-group input-group-sm">
                            <input name="SearchProduct" type="text" class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7 ml-auto" id="SearchProduct" placeholder="{{__('text.SearchProduct')}}" aria-describedby="SearchButton" value="{{ $query ?? '' }}">
                                <div class="input-group-append" id="SearchButton">
                                    <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search" aria-hidden="true"></i></button></div>
                            </div>
                        </form>
                    </div>
                </div>

                <form action="" method="POST" id="delete_form">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped tm-table-striped-even">
                            <colgroup>
                                <col span="1" style="width: 2%;"><!-- CHECKBOX -->
                                <col span="1" style="width: 55%;"><!-- PRODUCT NAME -->
                                <col span="1" style="width: 20%;"><!-- COUNT -->
                                <col span="1" style="width: 20%;"><!-- PRICE -->
                                <col span="1" style="width: 3%;"><!-- EDIT BUTTON -->
                             </colgroup>
                            <thead>
                                <tr class="tm-bg-gray">
                                    <th scope="col"><input type="checkbox" id="chk_all" name="chk_all" onclick="chkall.call(this)"></th>
                                <th scope="col">{{__('text.ProductName')}}</th>
                                    <th scope="col" class="text-center">{{__('text.InStocks')}}</th>
                                    <th scope="col" class="text-center">{{__('text.PriceEach')}}</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                <tr>
                                    <th scope="row">
                                        <input type="checkbox" aria-label="Checkbox" name="chk_id[]" class="chkbox" value="{{$product->id}}">
                                    </th>
                                    <td class="tm-product-name">{{$product->name}}</td>
                                    <td class="text-center">{{$product->stock_amount}}</td>
                                    <td class="text-center">{{$product->price}}</td>
                                    <td><a href="{{$product->id}}"><i class="fas fa-pencil-alt text-dark"></i></a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$products->links()}}
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function chkall(){
            var y = document.getElementsByClassName("chkbox");
            Array.from(y).forEach(element => {
                element.checked = this.checked;
            });
        }
    </script>
@endsection