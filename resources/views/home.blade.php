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
                                {{ __('Dashboard') }}
                                {{-- SORT DROPDOWN HERE --}}
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
                    <div class="row">
                        <div class="col">
                            <div class="card-header">
                                {{ __('Promotion') }}
                                {{-- SORT DROPDOWN HERE --}}
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
                        <form action="">
                            {{-- SEARCH CATEGORY AND FILTER --}}
                            <label for="name">{{ __('text.ProductName') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="">

                            <label for="category">{{ __('text.ProductCategory') }}</label>
                            <select class="form-control p-1" id="category" name="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                <option value="" selected>Any</option>
                            </select>
                            
                            <label for="rating">{{ __('text.MinRating') }}</label>
                            <div class="starRating text-center d-inline-block" id="rating">
                                <input id="rating5" class="star d-none" type="radio" name="rating" value="5">
                                <label for="rating5">5</label>
                                <input id="rating4" class="star d-none" type="radio" name="rating" value="4">
                                <label for="rating4">4</label>
                                <input id="rating3" class="star d-none" type="radio" name="rating" value="3">
                                <label for="rating3">3</label>
                                <input id="rating2" class="star d-none" type="radio" name="rating" value="2">
                                <label for="rating2">2</label>
                                <input id="rating1" class="star d-none" type="radio" name="rating" value="1">
                                <label for="rating1">1</label>
                            </div>

                            <br>
                            <label for="price_range">{{ __('text.PriceRange') }}</label>
                            <div class="form-group form-group-sm d-flex" id="price_range">
                                    <input type="number" class="form-control d-inline-block" id="min_price" name="min_price" value="" min="0" placeholder="{{ __('text.MinPrice') }}">
                                    <input type="number" class="form-control d-inline-block" id="max_price" name="max_price" value="" min="0" placeholder="{{ __('text.MaxPrice') }}">
                            </div>

                            <div class="row text-center">
                                <button type="submit" class="btn btn-success btn-sm mx-auto col-6 p-2">{{ __('text.ApplyFilter') }}</button>
                                <button type="reset" class="btn btn-warning btn-sm mx-auto col-6 p-2">{{ __('text.ResetFilter') }}</button>
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
             var y = document.getElementById('rating').getElementsByClassName('star');
            Array.from(y).forEach(element => {
               element.addEventListener("click", ratingUncheck);
            });

        function ratingUncheck() {
            var previousValue = $(this).data('storedValue');
            if (previousValue){
                $(this).prop('checked', !previousValue);
                $(this).data('storedValue', !previousValue);
            }else{
                $(this).data('storedValue', true);
                $(".star:input[type=radio]:not(:checked)").data("storedValue", false);
            }
        }
    </script>
@endsection
