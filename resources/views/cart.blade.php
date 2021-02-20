@extends('layouts.app')

@section('content')
{{-- {{session()->flush()}} --}}
{{-- {{dd(session('cart'))}} --}}
{{-- {{ dd($recommends) }} --}}

{{-- ========================================== GUEST ========================================== --}}
@guest
    <div class="container">
        <div class="row justify-content-center">
            {{-- CART --}}
            <div class="col-lg-9">
            <div class="card">
                    <div class="card-header">
                        {{__('text.ProductInCart')}}
                    </div>
                    <form action="{{route('cart.remove')}}" method="post">
                        @csrf
                        @method('delete')
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped tm-table-striped-even">
                                <colgroup>
                                    <col span="1" style="width: 5%;"><!-- CHECKBOX -->
                                    <col span="1" style="width: 28%;"><!-- IMG -->
                                    <col span="1" style="width: 26%;"><!-- NAME - AMOUNT -->
                                    <col span="1" style="width: 26%;"><!-- NAME - PRICE -->
                                    <col span="1" style="width: 5%;"><!-- REMOVE -->
                                </colgroup>
                                <thead>
                                    <tr class="tm-bg-gray">
                                        <th scope="col"><input type="checkbox" id="chk_all" name="chk_all"
                                                onclick="chkall.call(this)"></th>
                                        <th scope="col" class="text-center">{{ __('text.Image') }}</th>
                                        <th scope="col" class="text-center" colspan="2">{{ __('text.ProductInfo') }}</th>
                                        <th scope="col" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (Session::has('cart'))
                                    @foreach (Session::get('cart',[]) as $id => $item)
                                    <?php $product = App\Product::firstWhere('id',$item['product_id']) ?>
                                        <tr>
                                            <th scope="row" rowspan="2" class="align-middle">
                                                <input type="checkbox" aria-label="Checkbox" name="chk_id[]"
                                                class="chkbox" value="{{$product->id}}">
                                            </th>
                                            <td class="text-center" rowspan="2">
                                                @if ($product->image_img)
                                                <div id="product-{{$product->id}}" class="carousel slide" data-ride="carousel">
                                                    <ol class="carousel-indicators">
                                                        {{-- loop to image number
                                                        --}}
                                                        <?php $images = explode('|', $product->image_img);$x=0; ?>
                                                            @foreach ($images as $image)
                                                            <li data-target="#product-{{$product->id}}" data-slide-to="{{$x}}"></li>
                                                            <?php $x++ ?>
                                                            @endforeach
                                                    </ol>
                                                    <div class="carousel-inner">
                                                        {{-- loop to image number
                                                        --}}
                                                        <?php $y = 0; ?>
                                                        @foreach ($images as $image)
                                                        <div class="carousel-item @if($y==0) active @endif"> <?php $y = 1; ?>
                                                            <a href="{{ url('images/'.$image) }}" class="venobox"
                                                                data-gall="product-{{$product->id}}-images">
                                                                <img class="d-block cart-gallery-image rounded" src="{{ url('images/'.$image) }}">
                                                            </a>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <a class="carousel-control-prev" href="#product-{{$product->id}}" role="button" data-slide="prev">
                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Previous</span>
                                                    </a>
                                                    <a class="carousel-control-next" href="#product-{{$product->id}}" role="button" data-slide="next">
                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                        <span class="sr-only">Next</span>
                                                    </a>
                                                </div>
                                                @endif
                                                @if (!$product->image_img) <img class="d-block home-product-gallery-image"> @endif
                                            </td>
                                            <td class="tm-product-name" colspan="2"><a href="{{route('product.view',$product)}}" style="color:black">{{$product->name}}</a></td>
                                            <td rowspan="2"  class="align-middle text-center">
                                                <a href="{{route('cart.remove_single', $product->id)}}"><i class="fas fa-trash-alt text-dark"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center mein-font-15">{{__('text.Amount')}}:
                                                <input class="rounded border border-success text-center" type="number" name="buy_amount_{{$product->id}}" id="buy_amount_{{$product->id}}" value="{{$item['amount']}}" min="1" max="{{$product->stock_amount}}" onchange="totalprice({{$product->id}},{{$product->stock_amount}},{{$product->price}});ajaxamount({{$product->id}})">
                                            </td>
                                            <td class="text-center mein-font-15">{{__('text.TotalPrice')}}: <span id="total_price_{{$product->id}}">{{ $item['amount'] * $product->price}}</span>B</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group row mb-0">
                            <button type="submit" class="btn btn-sm btn-outline-danger ml-auto col-lg-12 col-xl-4">
                                {{ __('text.RemoveSelectedFromCart') }}
                            </button>
                        </div>
                    </div>
                    </form>
                </div>

                @if ($recommends)
                {{-- Recommend --}}
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                {{ __('text.RecommendedProduct') }}
                                {{-- SORT DROPDOWN HERE --}}
                            </div>
                            <div class="card-body d-flex flex-row flex-wrap">
                                {{-- card loop --}}
                                @foreach ($recommends as $recommend)
                                <div class="mein-card" >
                                    @if ($recommend->image_img)
                                    <div id="recommend-{{$recommend->id}}" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            {{-- loop to image number
                                            --}}
                                            <?php $recommend_images = explode('|', $recommend->image_img);$x=0; ?>
                                                @foreach ($recommend_images as $recommend_image)
                                                <li data-target="#recommend-{{$recommend->id}}" data-slide-to="{{$x}}"></li>
                                                <?php $x++ ?>
                                                @endforeach
                                        </ol>
                                        <div class="carousel-inner">
                                            {{-- loop to image number
                                            --}}
                                            <?php $y = 0; ?>
                                            @foreach ($recommend_images as $recommend_image)
                                            <div class="carousel-item @if($y==0) active @endif"> <?php $y = 1; ?>
                                                <a href="{{ url('images/'.$recommend_image) }}" class="venobox"
                                                    data-gall="recommend-{{$recommend->id}}-images">
                                                    <img class="d-block home-product-gallery-image" src="{{ url('images/'.$recommend_image) }}">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#recommend-{{$recommend->id}}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#recommend-{{$recommend->id}}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    @endif
                                    @if (!$recommend->image_img) <img class="d-block home-product-gallery-image"> @endif
                                    <div class="p-2">
                                    <p class="my-0 home-product-name"><a href="{{route('product.view',$recommend)}}" style="color:black">{{$recommend->name}}</a></p>
                                        <div class="my-0 home-product-price text-left">
                                            <p class="m-0">{{$recommend->price}}B</p>
                                            {{-- <span class="star-ratings-css"
                                                title=".{{(round($recommend->rating))}}"></span> --}}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- end card loop --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            {{-- COUPON --}}
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        {{__('text.Promotion')}} & {{__('text.Coupon')}}
                    </div>
                    <div class="card-body">
                        <p class="text-center my-0">{{__('text.TotalFinal')}}</p>
                        <p class="mein-font-x2 text-center my-0"><span id="currentValue">0</span>B</p>
                        <p class="text-center my-0 text-muted">-<span id="discountPercent">0</span>% (<s><span id="previousValue">0</span>B</s>)</p>

                        <div id="promotionDetails"></div>

                        @guest
                        <hr>
                        <div>
                            <p>{{__('text.ShippingAddress')}}</p>
                            <textarea name="guest_address" id="guest_address" rows="2" class="form-control p-1" onchange="guestAddress()">{{session()->get('address')}}</textarea>
                        </div>
                        @endguest

                        <hr>
                        {{-- <button class="btn btn-lg btn-outline-success w-100">Checkout <i class="fas fa-shopping-basket"></i></button> --}}
                        <div id="paypal-checkout-button"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endguest
{{-- ========================================== GUEST ========================================== --}}
{{-- ========================================== USER ========================================== --}}
@auth
<div class="container">
    <div class="row justify-content-center">
        {{-- CART --}}
        <div class="col-lg-9">
        <div class="card">
                <div class="card-header">
                    {{__('text.ProductInCart')}}
                </div>
                <form action="{{route('cart.remove')}}" method="post">
                    @csrf
                    @method('delete')
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped tm-table-striped-even">
                            <colgroup>
                                <col span="1" style="width: 5%;"><!-- CHECKBOX -->
                                <col span="1" style="width: 28%;"><!-- IMG -->
                                <col span="1" style="width: 26%;"><!-- NAME - AMOUNT -->
                                <col span="1" style="width: 26%;"><!-- NAME - PRICE -->
                                <col span="1" style="width: 5%;"><!-- REMOVE -->
                            </colgroup>
                            <thead>
                                <tr class="tm-bg-gray">
                                    <th scope="col"><input type="checkbox" id="chk_all" name="chk_all"
                                            onclick="chkall.call(this)"></th>
                                    <th scope="col" class="text-center">{{ __('text.Image') }}</th>
                                    <th scope="col" class="text-center" colspan="2">{{ __('text.ProductInfo') }}</th>
                                    <th scope="col" class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $cart = App\Cart::where('user_id', Auth::user()->id)->get() ?>
                                @if ($cart)
                                @foreach ($cart as $item)
                                <?php $product = App\Product::firstWhere('id',$item->product_id) ?>
                                    <tr>
                                        <th scope="row" rowspan="2" class="align-middle">
                                            <input type="checkbox" aria-label="Checkbox" name="chk_id[]"
                                            class="chkbox" value="{{$product->id}}">
                                        </th>
                                        <td class="text-center" rowspan="2">

                                            @if ($product->image_img)
                                            <div id="product-{{$product->id}}" class="carousel slide" data-ride="carousel">
                                                <ol class="carousel-indicators">
                                                    {{-- loop to image number
                                                    --}}
                                                    <?php $images = explode('|', $product->image_img);$x=0; ?>
                                                        @foreach ($images as $image)
                                                        <li data-target="#product-{{$product->id}}" data-slide-to="{{$x}}"></li>
                                                        <?php $x++ ?>
                                                        @endforeach
                                                </ol>
                                                <div class="carousel-inner">
                                                    {{-- loop to image number
                                                    --}}
                                                    <?php $y = 0; ?>
                                                    @foreach ($images as $image)
                                                    <div class="carousel-item @if($y==0) active @endif"> <?php $y = 1; ?>
                                                        <a href="{{ url('images/'.$image) }}" class="venobox"
                                                            data-gall="product-{{$product->id}}-images">
                                                            <img class="d-block cart-gallery-image rounded" src="{{ url('images/'.$image) }}">
                                                        </a>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <a class="carousel-control-prev" href="#product-{{$product->id}}" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#product-{{$product->id}}" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                            @endif
                                            @if (!$product->image_img) <img class="d-block home-product-gallery-image"> @endif

                                        </td>
                                        <td class="tm-product-name" colspan="2"><a href="{{route('product.view',$product)}}" style="color:black">{{$product->name}}</a></td>
                                        <td rowspan="2"  class="align-middle text-center">
                                            <a href="{{route('cart.remove_single', $product->id)}}"><i class="fas fa-trash-alt text-dark"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-center mein-font-15">{{__('text.Amount')}}:
                                            <input class="rounded border border-success text-center" type="number" name="buy_amount_{{$product->id}}" id="buy_amount_{{$product->id}}" value="{{$item->amount}}" min="1" max="{{$product->stock_amount}}" onchange="totalprice({{$product->id}},{{$product->stock_amount}},{{$product->price}});ajaxamount({{$product->id}})">
                                        </td>
                                        <td class="text-center mein-font-15">{{__('text.TotalPrice')}}: <span id="total_price_{{$product->id}}">{{ $item->amount * $product->price}}</span>B</td>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row mb-0">
                        <button type="submit" class="btn btn-sm btn-outline-danger ml-auto col-lg-12 col-xl-4">
                            {{ __('text.RemoveSelectedFromCart') }}
                        </button>
                    </div>
                </div>
                </form>
            </div>

            @if ($recommends)
                {{-- Recommend --}}
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                {{ __('text.RecommendedProduct') }}
                                {{-- SORT DROPDOWN HERE --}}
                            </div>
                            <div class="card-body d-flex flex-row flex-wrap">
                                {{-- card loop --}}
                                @foreach ($recommends as $recommend)
                                <div class="mein-card" >
                                    @if ($recommend->image_img)
                                    <div id="recommend-{{$recommend->id}}" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            {{-- loop to image number
                                            --}}
                                            <?php $recommend_images = explode('|', $recommend->image_img);$x=0; ?>
                                                @foreach ($recommend_images as $recommend_image)
                                                <li data-target="#recommend-{{$recommend->id}}" data-slide-to="{{$x}}"></li>
                                                <?php $x++ ?>
                                                @endforeach
                                        </ol>
                                        <div class="carousel-inner">
                                            {{-- loop to image number
                                            --}}
                                            <?php $y = 0; ?>
                                            @foreach ($recommend_images as $recommend_image)
                                            <div class="carousel-item @if($y==0) active @endif"> <?php $y = 1; ?>
                                                <a href="{{ url('images/'.$recommend_image) }}" class="venobox"
                                                    data-gall="recommend-{{$recommend->id}}-images">
                                                    <img class="d-block home-product-gallery-image" src="{{ url('images/'.$recommend_image) }}">
                                                </a>
                                            </div>
                                            @endforeach
                                        </div>
                                        <a class="carousel-control-prev" href="#recommend-{{$recommend->id}}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#recommend-{{$recommend->id}}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    @endif
                                    @if (!$recommend->image_img) <img class="d-block home-product-gallery-image"> @endif
                                    <div class="p-2">
                                    <p class="my-0 home-product-name"><a href="{{route('product.view',$recommend)}}" style="color:black">{{$recommend->name}}</a></p>
                                        <div class="my-0 home-product-price text-left">
                                            <p class="m-0">{{$recommend->price}}B</p>
                                            {{-- <span class="star-ratings-css"
                                                title=".{{(round($recommend->rating))}}"></span> --}}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- end card loop --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
        </div>
        {{-- COUPON --}}
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    {{__('text.Promotion')}} & {{__('text.Coupon')}}
                </div>
                <div class="card-body">
                    <p class="text-center my-0">{{__('text.TotalFinal')}}</p>
                    <p class="mein-font-x2 text-center my-0"><span id="currentValue">0</span>B</p>
                    <p class="text-center my-0 text-muted">-<span id="discountPercent">0</span>% (<s><span id="previousValue">0</span>B</s>)</p>

                    <div id="promotionDetails">

                    </div>

                    <hr>

                    <p>{{__('text.Coupon')}}</p>
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" placeholder="{{__('text.CouponCode')}}" aria-label="Coupon Code" oninput="this.value = this.value.replace(/\s/g, '').toUpperCase()"
                    style="text-transform: uppercase;" id="couponBox" onchange="couponSession()" value="{{session()->get('coupon')}}">
                        <div class="input-group-append" id="couponMark">
                          <span class="input-group-text"><i class="fas fa-times"></i></span>
                        </div>
                    </div>

                    {{-- <button class="btn btn-lg btn-outline-success w-100">Checkout <i class="fas fa-shopping-basket"></i></button> --}}
                    <div id="paypal-checkout-button">

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endauth
{{-- ========================================== USER ========================================== --}}

@endsection

@section('script')
<?php $payment = App\Settings::where('option','paypal_payment')->first() ?>
<script src="https://www.paypal.com/sdk/js?client-id={{$payment->value}}{{ (session()->get('locale') == 'th') ? '&currency=THB&locale=th_TH' : '&currency=THB&locale=en_US' }}"></script>
<script id="payment_script">
    var money = 0;
    paypal.Buttons({
        style:{
            color: 'black'
        },
        createOrder: function(data, actions) {
            @guest
            verifyAddress();
            @endguest
            ajaxfinalize();
            return actions.order.create({
                purchase_units: [{
                amount: {
                    value: money
                }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function() {
                @auth
                $.ajax({
                    type:'post',
                    url:"{{route('coupon.use')}}",
                    data:{"_token":"{{csrf_token()}}","coupon": $('#couponBox').get(0).value},
                    success:function(data){}
                });
                @endauth
                window.location = "cart/transaction/"+data.orderID;
            });
        },
        onCancel:function(data){

        },
        onInit: function(data, actions) {
            $('#guest_address').change(function() {
                if (verifyAddress()) {
                actions.enable();
                } else {
                actions.disable();
                }
            });
            verifyAddress();
        }
    }).render('#paypal-checkout-button');
</script>
<script>
    ajaxfinalize();
    $(document).ready(function() {
            $('.venobox').venobox({
                infinigall: true,
            });
        });

    function chkall() {
        var y = document.getElementsByClassName("chkbox");
        Array.from(y).forEach(element => {
            element.checked = this.checked;
        });
    }

    function totalprice(product_id,max_amount,price){
      var amount = $('#buy_amount_'+product_id).get(0);
      if(amount.value > max_amount){
        amount.value = max_amount;
      }
      $('#total_price_'+product_id).text(amount.value * price);
    }

    function ajaxamount(product_id){
        var amount = $('#buy_amount_'+product_id).get(0);
        $.ajax({
            type:'put',
            url:"{{route('cart.update')}}",
            data:{"_token":"{{csrf_token()}}","product_id":product_id,"buy_amount":amount.value},
            success:function(data){
                if(amount.value > data.max_amount){
                    amount.value = data.max_amount
                }
            }
        });
        ajaxfinalize();
    }

    function ajaxfinalize(){
        $.ajax({
            type:'get',
            url:"{{route('cart.finalize')}}",
            data:{"_token":"{{csrf_token()}}"},
            success:function(data){
                $('#currentValue').text((data.total).toFixed(2));
                $('#previousValue').text(data.prevtotal);
                $('#discountPercent').text((100-((data.total/data.prevtotal)*100)).toFixed(2));
                $('#promotionDetails').empty();
                $.each(data.promotion_applied, function (key, x) {
                    $('#promotionDetails').append('<p class="text-left my-0 mein-font-14"><span>'+x.promotion_name+'</span> <span>x'+x.count+'</span></p>');
                    $.each(x.products, function (key, y) {
                        $('#promotionDetails').append('<li class="mein-font-12">'+y+'</li>');
                    });
                });

                @auth
                $('#couponMark').empty();
                if(data.coupon_status){
                    $('#couponMark').append('<span class="input-group-text"><i class="fas fa-check"></i></span>');
                }else{
                    $('#couponMark').append('<span class="input-group-text"><i class="fas fa-times"></i></span>');
                }
                @endauth

                money = data.total;
            }
        });
    }

@auth
    function couponSession(){
        var code = $('#couponBox').get(0);
        $.ajax({
            type:'post',
            url:"{{route('cart.coupon')}}",
            data:{"_token":"{{csrf_token()}}","coupon":code.value},
            success:function(data){
                ajaxfinalize();
                console.log(data.valid)
            }
        });
    }
@endauth

@guest
    function guestAddress(){
        var guestaddress = $('#guest_address').val();
        $.ajax({
            type:'post',
            url:"{{route('cart.guest_address')}}",
            data:{"_token":"{{csrf_token()}}","guestaddress":guestaddress},
            success:function(data){
                console.log(data.valid)
            }
        });
    }
    function verifyAddress(){
        var guestaddress = $('#guest_address').val();
        if (guestaddress) {
            return true
        }
    }
@endguest
</script>
@endsection
