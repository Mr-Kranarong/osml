@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9 col-xl-8">
                <div class="card">
                    <div class="card-header row">
                        <div class="col-4">
                            {{ __('text.Coupon') }}
                        </div>
                        <div class="col-8 text-right">
                            <button data-backdrop="static" data-keyboard="false" data-toggle="modal"
                                data-target="#create-coupon-modal"
                                class="btn btn-sm btn-outline-success py-1">{{ __('text.CreateCoupon') }}</button>
                        </div>
                    </div>
                    <div class="card-body">

                        @foreach ($coupons as $coupon)
                            <div class="mein-collapsible">
                                <div class="row">
                                    <div class="col-6">
                                        <span class="mr-auto">{{ $coupon->name }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="ml-auto">{{ __('text.ValidUntil') }}:
                                            {{ date('d-m-Y', strtotime($coupon->expire_date)) }}</span>
                                    </div>
                                    <div class="col-2">
                                        <a href="#" class="mx-1" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#update-coupon-modal-{{$coupon->id}}"><i class="fas fa-pencil-alt text-dark"></i></a>
                                        <a href="{{ route('coupon.delete', $coupon) }}"
                                            onclick="event.preventDefault();document.getElementById('coupon-delete-{{ $coupon->id }}').submit()"
                                            class="mx-1"><i class="fas fa-trash-alt text-dark"></i></a>
                                        <form id="coupon-delete-{{ $coupon->id }}"
                                            action="{{ route('coupon.delete', $coupon) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('delete')
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="mein-content">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12 px-3">
                                        <br>
                                        <p class="m-0">{{ __('text.Code') }}: {{ $coupon->code }}</p>
                                        <p class="m-0">{{ __('text.DiscountType') }}:</p>
                                        @if ($coupon->discount_percentage)
                                            <p class="m-0">- {{ __('text.Cut') }} {{ $coupon->discount_percentage }}%
                                                {{ __('text.OfTotalCost') }}</p>
                                        @endif
                                        @if ($coupon->discount_amount)
                                            <p class="m-0">- {{ __('text.Cut') }} {{ $coupon->discount_amount }}
                                                {{ __('text.OfTotalCost') }}</p>
                                        @endif
                                        <br>
                                    </div>
                                    <div class="col-md-6 col-xs-12 px-3">
                                        <br>
                                        <p class="m-0">{{ __('text.Conditions') }}:</p>
                                        @if ($coupon->category_id)
                                            <p class="m-0">- {{ __('text.MustBeOfType') }}:
                                                {{ $categories->firstWhere('id', $coupon->category_id)->name }}</p>
                                        @endif
                                        @if ($coupon->min_total_price)
                                            <p class="m-0">- {{ __('text.CostOver') }} {{ $coupon->min_total_price }}</p>
                                        @endif
                                        @if ($coupon->max_total_price)
                                            <p class="m-0">- {{ __('text.CostLessThan') }} {{ $coupon->max_total_price }}
                                            </p>
                                        @endif
                                        <br>
                                    </div>
                                </div>
                            </div>

{{-- update coupon modal --}}
                        <div class="modal fade" id="update-coupon-modal-{{$coupon->id}}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form action="{{ route('coupon.update',$coupon) }}" method="POST" id="form-update-coupon-{{$coupon->id}}">
                @csrf
                @method('put')
                <div class="modal-header">
                    <h6 class="modal-title" id="create-coupon-modal-{{$coupon->id}}-label">{{ __('text.UpdateCoupon') }}</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="document.getElementById('form-update-coupon-{{$coupon->id}}').reset();formStateReset({{$coupon->id}});">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-8 px-4">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('text.CouponName') }}</label>
                                <input id="name{{$coupon->id}}" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{$coupon->name}}" required autocomplete="name" autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col px-4">
                            <div class="form-group">
                                <label for="expire_date" class="col-form-label">{{ __('text.ExpireDate') }}</label>
                                <input id="expire_date{{$coupon->id}}" type="date"
                                    class="form-control @error('expire_date') is-invalid @enderror" name="expire_date"
                            required autocomplete="expire_date" value="{{$coupon->expire_date}}" autofocus>
                                @error('expire_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-5 px-4">
                            <div class="form-group">
                                <label for="code" class="col-form-label">{{ __('text.Code') }}</label>
                                <input id="code{{$coupon->id}}" type="text" class="form-control @error('code') is-invalid @enderror"
                                    name="code" value="{{$coupon->code}}" required autocomplete="code" autofocus
                                    oninput="this.value = this.value.replace(/\s/g, '').toUpperCase()"
                            style="text-transform: uppercase;">
                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <p class="col-form-label @error('discount_type') is-invalid @enderror">
                                    {{ __('text.DiscountType') }}</p>
                                @error('discount_type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                <input id="percentage{{$coupon->id}}" type="radio" name="discount_type" value="0" required
                                    autocomplete="discount_type" autofocus onclick="couponDefaultDiscount({{$coupon->id}})" @if ($coupon->discount_percentage)
                                        checked=""
                                    @endif>
                                <label for="percentage" class="col-form-label">{{ __('text.ByPercentageOf') }}</label>
                                <input id="discount_percentage{{$coupon->id}}" type="number" class="" name="discount_percentage"
                            required autocomplete="discount_percentage" max="100" min="0" value="{{$coupon->discount_percentage}}" autofocus @if (!$coupon->discount_percentage)
                            disabled
                        @endif>
                                @error('discount_percentage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <br>
                                <input id="amount{{$coupon->id}}" type="radio" name="discount_type" value="1"
                                    autocomplete="discount_type" autofocus onclick="couponDefaultDiscount({{$coupon->id}})" @if ($coupon->discount_amount)
                                    checked=""
                                @endif>
                                <label for="amount" class="col-form-label">{{ __('text.ByTotalSumOf') }}</label>
                                <input id="discount_amount{{$coupon->id}}" type="number" class="" min="0" name="discount_amount"
                                    required autocomplete="discount_amount" value="{{$coupon->discount_amount}}" autofocus @if (!$coupon->discount_amount)
                                    disabled
                                @endif>
                                @error('discount_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </div>
                        </div>
                        <div class="col px-4">
                            <div class="form-group">
                                <p class="col-form-label">
                                    {{ __('text.Conditions') }}</p>
                                <input type="checkbox" id="category_condition{{$coupon->id}}" name="category_condition"
                                    onclick="conditionToggler(this)" @if ($coupon->category_id)
                                    checked=""
                                    @endif>
                                <label for="category_condition"
                                    class="col-form-label">{{ __('text.MustBeOfType') }}</label>
                                <select class="" id="category_id{{$coupon->id}}" name="category_id" autocomplete="category" autofocus
                                    @if (!$coupon->category_id)
                                    disabled
                                    @endif>
                                    @forelse($categories as $category)
                                        <option value="{{ $category->id }}" @if ($coupon->category_id == $category->id)
                                            selected=""
                                        @endif>{{ $category->name }}</option>
                                    @empty
                                        <option value="">None</option>
                                    @endforelse
                                </select>
                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <br>
                                <input type="checkbox" class="" id="min_condition{{$coupon->id}}" name="min_condition"
                                    onclick="conditionToggler(this)" @if ($coupon->min_total_price)
                                    checked=""
                                    @endif>
                                <label for="min_condition" class="col-form-label">{{ __('text.CostOver') }}</label>
                                <input id="min_total_price{{$coupon->id}}" type="number" min="0" name="min_total_price"
                                    required autocomplete="min_total_price" autofocus @if (!$coupon->min_total_price)
                                    disabled
                            @endif value="{{$coupon->min_total_price}}">
                                @error('min_total_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <br>
                                <input type="checkbox" class="" id="max_condition{{$coupon->id}}" name="max_condition"
                                    onclick="conditionToggler(this)" @if ($coupon->max_total_price)
                                    checked=""
                                    @endif>
                                <label for="max_condition" class="col-form-label">{{ __('text.CostLessThan') }}</label>
                                <input id="max_total_price{{$coupon->id}}" type="number" class="" min="0" name="max_total_price"
                                    required autocomplete="max_total_price" autofocus @if (!$coupon->max_total_price)
                                    disabled
                            @endif value="{{$coupon->max_total_price}}">
                                @error('max_total_price')
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
                        onclick="document.getElementById('form-update-coupon-{{$coupon->id}}').reset();formStateReset({{$coupon->id}});">{{ __('text.CancelAction') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('text.ConfirmAction') }}</button>
                </div>
            </form>
        </div>

    </div>
</div>
{{-- END update-coupon  MODAL --}}

                        @endforeach

                    </div>
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- create coupon modal --}}
    <div class="modal fade" id="create-coupon-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <form action="{{ route('coupon.create') }}" method="POST" id="form-create-coupon">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title" id="create-coupon-modal-label">{{ __('text.CreateCoupon') }}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="document.getElementById('form-create-coupon').reset();formStateReset();">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xl-8 px-4">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('text.CouponName') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col px-4">
                                <div class="form-group">
                                    <label for="expire_date" class="col-form-label">{{ __('text.ExpireDate') }}</label>
                                    <input id="expire_date" type="date"
                                        class="form-control @error('expire_date') is-invalid @enderror" name="expire_date"
                                        required autocomplete="expire_date" autofocus>
                                    @error('expire_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-5 px-4">
                                <div class="form-group">
                                    <label for="code" class="col-form-label">{{ __('text.Code') }}</label>
                                    <input id="code" type="text" class="form-control @error('code') is-invalid @enderror"
                                        name="code" value="" required autocomplete="code" autofocus
                                        oninput="this.value = this.value.replace(/\s/g, '').toUpperCase()"
                                        style="text-transform: uppercase;">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <p class="col-form-label @error('discount_type') is-invalid @enderror">
                                        {{ __('text.DiscountType') }}</p>
                                    @error('discount_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <input id="percentage" type="radio" name="discount_type" value="0" required
                                        autocomplete="discount_type" autofocus onclick="couponDefaultDiscount('')">
                                    <label for="percentage" class="col-form-label">{{ __('text.ByPercentageOf') }}</label>
                                    <input id="discount_percentage" type="number" class="" name="discount_percentage"
                                        required autocomplete="discount_percentage" max="100" min="0" autofocus disabled>
                                    @error('discount_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <br>
                                    <input id="amount" type="radio" name="discount_type" value="1"
                                        autocomplete="discount_type" autofocus onclick="couponDefaultDiscount('')">
                                    <label for="amount" class="col-form-label">{{ __('text.ByTotalSumOf') }}</label>
                                    <input id="discount_amount" type="number" class="" min="0" name="discount_amount"
                                        required autocomplete="discount_amount" autofocus disabled>
                                    @error('discount_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <div class="col px-4">
                                <div class="form-group">
                                    <p class="col-form-label">
                                        {{ __('text.Conditions') }}</p>
                                    <input type="checkbox" class="" id="category_condition" name="category_condition"
                                        onclick="conditionToggler(this)">
                                    <label for="category_condition"
                                        class="col-form-label">{{ __('text.MustBeOfType') }}</label>
                                    <select class="" id="category_id" name="category_id" autocomplete="category" autofocus
                                        disabled>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @empty
                                            <option value="">None</option>
                                        @endforelse
                                    </select>
                                    @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <br>
                                    <input type="checkbox" class="" id="min_condition" name="min_condition"
                                        onclick="conditionToggler(this)">
                                    <label for="min_condition" class="col-form-label">{{ __('text.CostOver') }}</label>
                                    <input id="min_total_price" type="number" class="" min="0" name="min_total_price"
                                        required autocomplete="min_total_price" autofocus disabled>
                                    @error('min_total_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <br>
                                    <input type="checkbox" class="" id="max_condition" name="max_condition"
                                        onclick="conditionToggler(this)">
                                    <label for="max_condition" class="col-form-label">{{ __('text.CostLessThan') }}</label>
                                    <input id="max_total_price" type="number" class="" min="0" name="max_total_price"
                                        required autocomplete="max_total_price" autofocus disabled>
                                    @error('max_total_price')
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
    {{-- END create-coupon MODAL --}}
@endsection

@section('script')
<script>
    var coll = document.getElementsByClassName("mein-collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("mein-active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }

    $(document).ready(function() {
        
    });

    function couponDefaultDiscount(id) {
        if ($('#percentage'+id).is(':checked')) {
            $('#discount_percentage'+id).prop("disabled", false);
            $('#discount_amount'+id).prop("disabled", true);
        } else {
            $('#discount_percentage'+id).prop("disabled", true);
            $('#discount_amount'+id).prop("disabled", false);
        }
    }

    function conditionToggler(e) {
        if ($(e).is(':checked')) {
            $(e).nextAll().eq(1).prop("disabled", false);
        } else {
            $(e).nextAll().eq(1).prop("disabled", true);
        }
    }

    function formStateReset(id){
        couponDefaultDiscount(id);
        conditionToggler('#category_condition'+id);
        conditionToggler('#min_condition'+id);
        conditionToggler('#max_condition'+id);
    }
</script>
@endsection
