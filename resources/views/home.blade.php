@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{-- PRODUCT LIST SECTION --}}
            <div class="col-lg-9">
                <div class="card">
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                {{ __('text.TotalProduct') }} ({{$total}})
                                {{-- SORT DROPDOWN HERE --}}
                            </div>
                            <div class="card-body d-flex flex-row flex-wrap">
                                {{-- card loop --}}
                                @foreach ($products as $product)
                                <div class="mein-card" >
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
                                                    <img class="d-block home-product-gallery-image" src="{{ url('images/'.$image) }}">
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
                                    <div class="p-2">
                                    <p class="my-0 home-product-name"><a href="{{route('product.view',$product)}}" style="color:black">{{$product->name}}</a></p>
                                        <div class="my-0 home-product-price text-left">
                                            <p class="m-0">{{$product->price}}B</p>
                                            <span class="star-ratings-css"
                                                title=".{{(round($product->rating))}}"></span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                {{-- end card loop --}}
                            </div>
                            {{ $products->links() }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                {{ __('Promotion') }}
                            </div>
                            <div class="card-body">
                                @auth
                                    {{ __('You are logged in!') }}
                                    @if (Auth::user()->hasAccess())
                                        And You're fucking admin
                                    @endif
                                @else
                                    Why not login bro?
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- FILTER SECTION --}}
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        {{ __('text.Filter') }}
                    </div>
                    <div class="card-body">
                    <form action="{{route('home.filter')}}" method="get">
                            {{-- SEARCH CATEGORY AND FILTER --}}
                            <label for="name">{{ __('text.ProductName') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ app('request')->input('name') }}">

                            <label for="category">{{ __('text.ProductCategory') }}</label>
                            <select class="form-control p-1" id="category" name="category">
                                <option value="">Any</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if (app('request')->input('category')==$category->id) selected="" @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <label for="rating">{{ __('text.MinRating') }}</label>
                            <div class="starRating text-center d-inline-block" id="rating">
                                <input id="rating5" class="star d-none" type="radio" name="rating" value="5" @if (app('request')->input('rating')==5) checked="" @endif>
                                <label for="rating5">5</label>
                                <input id="rating4" class="star d-none" type="radio" name="rating" value="4" @if (app('request')->input('rating')==4) checked="" @endif>
                                <label for="rating4">4</label>
                                <input id="rating3" class="star d-none" type="radio" name="rating" value="3" @if (app('request')->input('rating')==3) checked="" @endif>
                                <label for="rating3">3</label>
                                <input id="rating2" class="star d-none" type="radio" name="rating" value="2" @if (app('request')->input('rating')==2) checked="" @endif>
                                <label for="rating2">2</label>
                                <input id="rating1" class="star d-none" type="radio" name="rating" value="1" @if (app('request')->input('rating')==1) checked="" @endif>
                                <label for="rating1">1</label>
                            </div>

                            <br>
                            <label for="price_range">{{ __('text.PriceRange') }}</label>
                            <div class="form-group form-group-sm d-flex" id="price_range">
                                <input type="number" class="form-control d-inline-block" id="min_price" name="min_price"
                                    value="{{ app('request')->input('min_price') }}" min="0" placeholder="{{ __('text.MinPrice') }}">
                                <input type="number" class="form-control d-inline-block" id="max_price" name="max_price"
                                    value="{{ app('request')->input('max_price') }}" min="0" placeholder="{{ __('text.MaxPrice') }}">
                            </div>

                            <div class="row text-center">
                                <button type="submit"
                                    class="btn btn-success btn-sm mx-auto col-6 p-2">{{ __('text.ApplyFilter') }}</button>
                            <a class="btn btn-warning btn-sm mx-auto col-6 p-2" href="{{route('home')}}">{{ __('text.ResetFilter') }}</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        
        $(document).ready(function() {
            $('.venobox').venobox({
                infinigall: true,
            });
        });

        var y = document.getElementById('rating').getElementsByClassName('star');
        Array.from(y).forEach(element => {
            element.addEventListener("click", ratingUncheck);
        });

        function ratingUncheck() {
            var previousValue = $(this).data('storedValue');
            if (previousValue) {
                $(this).prop('checked', !previousValue);
                $(this).data('storedValue', !previousValue);
            } else {
                $(this).data('storedValue', true);
                $(".star:input[type=radio]:not(:checked)").data("storedValue", false);
            }
        }

    </script>
@endsection
