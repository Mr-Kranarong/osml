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
                    @if (!$product->image_img) <img class="d-block product-gallery-image rounded"> @endif
                </div>
                {{-- details --}}
                <div class="col-lg-6 p-4 my-auto">
                    <form action="{{route('cart.add')}}" method="post">
                    @csrf
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <p class="product-details-text">{{__('text.ProductName')}}: {{$product->name}}</p>
                        <p class="product-details-text">{{__('text.ProductCategory')}}: {{$product_category->name}}</p>
                        <p class="product-details-text">{{__('text.Rating')}}: <span class="star-ratings-css" title=".{{(round($product->rating))}}"></span></p>
                        <p class="product-details-text">{{__('text.InStocks')}}: {{$product->stock_amount}}</p>
                        <p class="product-details-text">{{__('text.PriceEach')}}: {{$product->price}}B</p>
                        <p class="product-details-text">{{__('text.BuyAmount')}}:
                            <button type="button" class="btn-outline-success border no-outline" onclick="buyamount(0);totalprice()">-</button>
                            <input class="rounded border border-success text-center" type="number" name="buy_amount" id="buy_amount" value="1" min="1" max="{{$product->stock_amount}}" onchange="totalprice()">
                            <button type="button"  class="btn-outline-success border no-outline" onclick="buyamount(1);totalprice()">+</button>
                        </p>
                        <p class="product-details-text">{{__('text.TotalPrice')}}: <span id="total_price">0</span>B</p>

                        @if (is_null(Auth::user()) || !Auth::user()->hasAccess())
                        <div class="row">
                            <div class="col">
                            <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                {{__('text.AddToCart')}}
                            </button>
                            </div>
                            @auth

                            <div class="col-sm-6">
                            <button type="button" class="btn btn-sm btn-outline-danger w-100 @if (!$favorite) d-none @endif" id="removeWishlist" onclick="wishlistToggler(0)">
                                {{__('text.RemoveFromWishlist')}}
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-warning w-100 @if ($favorite) d-none @endif" id="addWishlist" onclick="wishlistToggler(1)">
                                {{__('text.AddToWishlist')}}
                            </button>
                            </div>
                            @endauth
                        </div>
                        @endif
                        @if (Auth::user() && Auth::user()->hasAccess())
                        @if (!$product->promotion_id)
                        <div class="row">
                            <div class="col">
                                <a href="{{route('product.promotion.create', $product)}}" class="btn btn-sm btn-outline-dark w-100">
                                    {{__('text.CreatePromotion')}}
                                </a>
                            </div>
                        </div>
                        @endif
                        @endif
                    </form>
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
                <div class="card-header row">
                  <div class="col-6">
                    {{__('text.ProductReview')}}
                  </div>
                  @auth
                  <?php
                        $canReview = App\Purchase_Order::where('product_id','like',"$product->id")->where('processed_status','=',"1")->where('user_id','like',Auth::user()->id)->count();
                        $hasReviewed = App\Product_Review::where('product_id','like',"$product->id")->where('user_id','like',Auth::user()->id)->count();
                  ?>
                  @if ($canReview > 0)
                    @if ($hasReviewed > 0)
                    @else
                    <div class="col-6 text-right">
                        <button data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                    data-target="#write-review-modal"
                                    class="btn btn-sm btn-outline-primary py-1">{{ __('text.WriteReview') }}</button>
                    </div>
                    @endif
                  @endif
                  @endauth
                </div>
                <div class="card-body">
                  <p class="product-details-text my-1"><span class="mein-font-x2">@if ($product->rating) {{round($product->rating,1)}} @else 0 @endif/ 5</span> <span class="star-ratings-css" style="font-size: 2.0em" title=".{{(round($product->rating))}}"></span><span class="mein-font-x2">({{$total_review}})</span></p>
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
                  <div>
                    <p class="mein-font-14 mb-0"><strong>Q:</strong> {{$question->question}}</p>
                    <p class="mein-font-14 mb-0"><strong>A:</strong> {{$question->response}}</p>
                    <p class="float-right">{{__('text.AskedBy')}} {{$question->name}}</p>
                  </div>
                    <hr>
                  @endforeach
                  {{ $questions->appends(['reviews' => $reviews->currentPage()])->links() }}
                </div>
                @auth
                <div class="card-footer row">
                    <div class="col text-right">
                        <button data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                    data-target="#write-question-modal"
                                    class="btn btn-sm btn-outline-primary py-1">{{ __('text.WriteQuestion') }}</button>
                    </div>
                </div>
                @endauth
              </div>
            </div>
        </div>
    </div>

    @auth
    <div class="modal fade" id="write-review-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="{{ route('review.create',['product_id' => $product->id]) }}" method="POST" id="form-write-review">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="write-review-modal-label">{{ __('text.WriteReview') }}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="document.getElementById('form-write-review').reset();formStateReset();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="content" class="col-form-label">{{ __('text.Content') }}</label>
                                    <textarea id="content" class="form-control @error('content') is-invalid @enderror"
                                        name="content" value="" required autocomplete="content" autofocus rows="2"></textarea>
                                    @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label id="reviewratingText" for="reviewrating" class="col-form-label">{{ __('text.Rating') }}</label>
                                    <input class="mein-width-100" type="range" id="reviewrating" name="reviewrating" min="1" max="5">
                                    @error('reviewrating')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="document.getElementById('form-create-coupon').reset();formStateReset();">{{ __('text.CancelAction') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('text.ConfirmAction') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="write-question-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="{{ route('question.create',['product_id' => $product->id]) }}" method="POST" id="form-write-question">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="write-question-modal-label">{{ __('text.WriteQuestion') }}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="document.getElementById('form-write-question').reset();formStateReset();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="content" class="col-form-label">{{ __('text.Content') }}</label>
                                    <textarea id="content" class="form-control @error('content') is-invalid @enderror"
                                        name="content" value="" required autocomplete="content" autofocus rows="2"></textarea>
                                    @error('content')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="document.getElementById('form-create-coupon').reset();formStateReset();">{{ __('text.CancelAction') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('text.ConfirmAction') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    @endauth
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

    function wishlistToggler(status){
        var addWishlistButton = document.getElementById('addWishlist');
        var removeWishlistButton = document.getElementById('removeWishlist');
        if(status==0){
          $.ajax({
               type:'delete',
               url:"{{route('favorite.remove')}}",
               data:{"_token": "{{ csrf_token() }}",'product_id': {{$product->id}}},
               success:function(data) {
                  removeWishlistButton.classList.add("d-none");
                  addWishlistButton.classList.remove("d-none");
               }
            });
        }else if(status==1){
          $.ajax({
               type:'post',
               url:"{{route('favorite.add')}}",
               data:{"_token": "{{ csrf_token() }}",'product_id': {{$product->id}}},
               success:function(data) {
                addWishlistButton.classList.add("d-none");
                removeWishlistButton.classList.remove("d-none");
               }
            });
        }
    }
</script>
@endsection
