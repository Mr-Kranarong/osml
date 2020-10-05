@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            {{-- product images, and details row--}}
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
                </div>
                {{-- details --}}
                <div class="col-lg-6 p-4 my-auto">
                      <p class="product-details-text">{{__('text.ProductName')}}: {{$product->name}}</p>
                      <p class="product-details-text">{{__('text.ProductCategory')}}: {{$product_category->name}}</p>
                      <p class="product-details-text">{{__('text.Rating')}}: <span class="star-ratings-css" title=".{{(round($product->rating))}}"></span></p>
                      <p class="product-details-text">{{__('text.InStocks')}}: {{$product->stock_amount}}</p>
                      <p class="product-details-text">{{__('text.PriceEach')}}: {{$product->price}}B</p>
                      <p class="product-details-text">{{__('text.BuyAmount')}}: 
                          <button class="btn-outline-success border no-outline" onclick="buyamount(0);totalprice()">-</button>
                          <input class="rounded border border-success text-center" type="number" name="buy_amount" id="buy_amount" value="1" min="1" max="{{$product->stock_amount}}" onchange="totalprice()">
                          <button  class="btn-outline-success border no-outline" onclick="buyamount(1);totalprice()">+</button>
                      </p>
                      <p class="product-details-text">{{__('text.TotalPrice')}}: <span id="total_price">0</span>B</p>
                      <div class="row">
                        <div class="col">
                          <button class="btn btn-sm btn-outline-success w-100">
                            {{__('text.AddToCart')}}
                          </button>
                        </div>
                        @auth
                        <div class="col">
                          <button class="btn btn-sm btn-outline-warning w-100">
                            {{__('text.AddToWishlist')}}
                          </button>
                        </div>
                      </div>
                        @endauth
                </div>
            </div>
            {{-- description row --}}
            <div class="row">
              <div class="col">
                <div class="card-header">
                  {{__('text.ProductDescription')}}
                </div>
                <div class="card-body">
                  {!! $product->description !!}
                </div>
              </div>
            </div>
            {{-- review row --}}
            <div class="row">
              <div class="col">
                <div class="card-header">
                  {{__('text.ProductReview')}}
                </div>
                <div class="card-body">
                  <p class="product-details-text my-1"><span class="mein-font-x2">@if ($product->rating) {{round($product->rating,1)}} @else 0 @endif/ 5</span> <span class="star-ratings-css" style="font-size: 2.5em" title=".{{(round($product->rating))}}"></span><span class="mein-font-x2">({{$total_review}})</span></p>
                  @foreach ($reviews as $review)
                  <blockquote class="blockquote text-center">
                    <p class="mb-0">"{{$review->description}}"</p>
                  <footer class="blockquote-footer">{{$review->name}}</footer>
                  </blockquote>
                  @endforeach
                  {{ $reviews->appends(['questions' => $questions->currentPage()])->links() }}
                </div>
              </div>
            </div>
            {{-- question row --}}
            <div class="row">
              <div class="col">
                <div class="card-header row">
                  <div class="col">
                    {{__('text.ProductQuestion')}}
                  </div>
                  <div class="col">
                    <form action="{{ route('product.view',$product) }}" method="get">
                      @csrf
                      <div class="input-group input-group-sm">
                          <input name="SearchQuestion" type="text"
                              class="form-control validate col-xl-9 col-lg-8 col-md-8 col-sm-7 ml-auto"
                              id="SearchQuestion" placeholder="{{ __('text.SearchQuestion') }}"
                              aria-describedby="SearchButton" value="{{ $query ?? '' }}">
                          <div class="input-group-append" id="SearchButton">
                              <button type="submit" class="btn btn-outline-secondary"><i class="fa fa-search"
                                      aria-hidden="true"></i></button></div>
                      </div>
                  </form>
                  </div>
                </div>
                <div class="card-body">
                  @foreach ($questions as $question)
                    <p><strong>Q:</strong> {{$question->question}}</p>
                    <p><strong>A:</strong> {{$question->response}}</p>
                    <hr>
                  @endforeach
                  {{ $questions->appends(['reviews' => $reviews->currentPage()])->links() }}
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
  totalprice();

  $(document).ready(function() {
            $('.venobox').venobox({
                infinigall: true,
            });
        });

    function buyamount(type){
      var amount = $('#buy_amount').get(0);
      if(type==0 && amount.value > 1){
        amount.value--;
      }else if(type==1 && amount.value < {{$product->stock_amount}}){
        amount.value++;
      }
    }

    function totalprice(){
      var amount = $('#buy_amount').get(0);
      if(amount.value > {{$product->stock_amount}}){
        amount.value = {{$product->stock_amount}};
      }
      $('#total_price').text(amount.value * {{$product->price}});
    }
</script>
@endsection
