@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" id="the-product">
                    <div class="card-header">
                        {{__('text.CreatePromotion')}}
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            {{-- images --}}
                            <div class="col-lg-6 p-4">
                              @if ($product->image_img)
                                <div id="carousel-thumb-{{$product->id}}" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                                    <?php $images = explode('|', $product->image_img);$x=0; ?>
                                    <div class="carousel-inner" role="listbox">
                                    @foreach ($images as $image)
                                        <div class="carousel-item @if($x==0) active @endif"><?php $x = 1; ?>
                                          <a href="{{ url('images/'.$image) }}" class="venobox"
                                                                data-gall="product-{{$product->id}}-images">
                                            <img class="d-block product-gallery-image rounded" src="{{ url('images/'.$image) }}">
                                          </a>
                                        </div>
                                    @endforeach
                                  </div>

                                    <a class="carousel-control-prev" href="#carousel-thumb-{{$product->id}}" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carousel-thumb-{{$product->id}}" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>

                                    <ol class="carousel-indicators">
                                      <?php $y=0; ?>
                                      @foreach ($images as $image)
                                        <li data-target="#carousel-thumb-{{$product->id}}" data-slide-to="{{$y}}"> <img class="d-block w-100"
                                                src="{{ url('images/'.$image) }}"
                                                class="img-fluid"></li>
                                                <?php $y++ ?>
                                      @endforeach
                                    </ol>
                                </div>
                                @endif
                                @if (!$product->image_img) <img class="d-block product-gallery-image rounded"> @endif
                            </div>
                            {{-- details --}}
                            <div class="col-lg-6 p-4 my-auto">
                                    <input type="hidden" name="product_id" value="{{$product->id}}">
                                    <p class="product-details-text">{{__('text.ProductName')}}: {{$product->name}}</p>
                                    <p class="product-details-text">{{__('text.InStocks')}}: {{$product->stock_amount}}</p>
                                    <p class="product-details-text">{{__('text.PriceEach')}}: {{$product->price}}B</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col text-center">
                                            <p class="product-details-text"><u>{{__('text.TotalPrice')}}</u></p>
                                            <p class="h2 bold" id="totalsum"></p>
                                        </div>
                                    </div>
                                    @if (!$product->promotion_id)
                                    <div class="row">
                                        <div class="col text-center">
                                            <p class="product-details-text"><u>{{__('text.NewTotal')}}</u></p>
                                            <input type="number" class="bold form-control form-control-sm" id="totalnew" value="{{$product->price}}" onchange="setTotal();document.getElementById('promotiontotal').value = this.value;" required>
                                        </div>
                                        <div class="col text-center">
                                            <p class="product-details-text"><u>{{__('text.Discount')}}</u></p>
                                            <p class="h2 bold" id="discount">0</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
                                            <p class="product-details-text"><u>{{__('text.PromotionName')}}</u></p>
                                            <input type="text" class="bold form-control form-control-sm" id="proname" value="" onchange="setTotal();document.getElementById('promotionname').value = this.value;" required>
                                        </div>
                                        <div class="col text-center">
                                            <p class="product-details-text"><u>{{__('text.PromotionExpireDate')}}</u></p>
                                            <input type="date" class="bold form-control form-control-sm" id="proname" value="" onchange="setTotal();document.getElementById('promotiondate').value = this.value;" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col text-center">
                                            <a href="" class="btn btn-sm btn-outline-dark w-100" onclick="event.preventDefault();document.getElementById('create-promotion-form').submit()">
                                                {{__('text.CreatePromotion')}}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                            </div>
                        </div>
                        @if (!$product->promotion_id)
                        <hr>
                        <div id="first-product">

                        </div>
                        <hr>
                        <div id="second-product">

                        </div>
                        <form action="{{ route('product.promotion.store') }}" method="POST" id="create-promotion-form">
                        @csrf
                            <input type="hidden" id="one" name="one" value="{{$product->id}}">
                            <input type="hidden" id="two" name="two" value="">
                            <input type="hidden" id="three" name="three" value="">
                            <input type="hidden" id="promotiontotal" name="promotiontotal" value="{{$product->price}}">
                            <input type="hidden" id="promotionname" name="promotionname" value="">
                            <input type="hidden" id="promotiondate" name="promotiondate" value="">
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    var a ={{$product->price}};
    var b = 0;
    var c = 0;


    var x ={{$product->id}};
    ajaxApriori(1,x,0);
    setTotal();

    $(document).ready(function() {
        $('.venobox').venobox({
            infinigall: true,
        });
    });


    function setB(price){
        b = price;
        c = 0
        setTotal();
    }
    function setC(price){
        c = price;
        setTotal();
    }
    function setTotal(){
        var newtotal = $("#totalnew").val();
        var zxc = a+b+c;

        $("#totalsum").html(zxc);
        $("#discount").html((((100-((newtotal/zxc)*100)).toFixed(2))*-1)+'%');
    }

    function ajaxApriori(itemNo, productA, productB){
        if(itemNo==1){
          $.ajax({
               type:'get',
               url:"{{route('product.promotion.apriori')}}",
               data:{"_token": "{{ csrf_token() }}","productA":productA,"productB":productB,"itemNo":itemNo},
               success:function(data) {
                $("#first-product").empty();
                if(data != 0){
                    $("#first-product").append("<h5>{{__('text.ProductRecommendation')}} - 1</h5>");
                    $("#first-product").append("<div class='col-sm-12'>");
                    data.forEach(element => {
                        $("#first-product").append("<div class='radio'>");
                        $("#first-product").append("<label><input type='radio' name='first' id='first"+element.id+"' value='"+element.id+"' onclick='ajaxApriori(2,"+x+",this.value);setB("+element.price+");document.getElementById(\"two\").value = this.value;'> "+element.name+" - {{__('text.Price')}} "+element.price+"</label>");
                        $("#first-product").append("</div>");
                    });
                    $("#first-product").append("</div>");
                }
               }
            });
        }else if(itemNo==2){
          $.ajax({
               type:'get',
               url:"{{route('product.promotion.apriori')}}",
               data:{"_token": "{{ csrf_token() }}","productA":productA,"productB":productB,"itemNo":itemNo},
               success:function(data) {
                $("#second-product").empty();
                if(data != 0){
                    $("#second-product").append("<h5>{{__('text.ProductRecommendation')}} - 2</h5>");
                    $("#second-product").append("<div class='col-sm-12'>");
                    data.forEach(element => {
                        $("#second-product").append("<div class='radio'>");
                        $("#second-product").append("<label><input type='radio' name='second' id='second"+element.id+"' value='"+element.id+"' onclick='setC("+element.price+");document.getElementById(\"three\").value = this.value;'> "+element.name+" - {{__('text.Price')}} "+element.price+"</label>");
                        $("#second-product").append("</div>");
                    });
                    $("#second-product").append("</div>");
                }
               }
            });
        }
    }
</script>
@endsection
