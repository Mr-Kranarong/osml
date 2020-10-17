@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('text.CreateProduct') }}</div>

                    <div class="card-body">
                        <form action="{{ route('product.store') }}" method="POST" id="form-product-create"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="name"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductName') }}</label>

                                <div class="col-xl-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductDescription') }}</label>

                                <div class="col-xl-6">
                                    <div id="descriptionEditor"></div>

                                    <textarea id="description" name="description"
                                        class="form-control @error('description') is-invalid @enderror d-none" rows="3" required
                                        autocomplete="description" autofocus></textarea>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="category"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductCategory') }}</label>

                                <div class="col-xl-6">
                                    <select class="form-control p-1" id="category" name="category" required
                                        autocomplete="category" autofocus>
                                        @forelse($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @empty
                                            <option value="">None</option>
                                        @endforelse
                                    </select>

                                    @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="price"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.PriceEach') }}</label>

                                <div class="col-xl-6">
                                    <input id="price" type="number"
                                        class="form-control @error('price') is-invalid @enderror" name="price" value=""
                                        required autocomplete="price" autofocus>

                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="stock_amount"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.InStocks') }}</label>

                                <div class="col-xl-6">
                                    <input id="stock_amount" type="number"
                                        class="form-control @error('stock_amount') is-invalid @enderror" name="stock_amount"
                                        value="" required autocomplete="stock_amount" autofocus>

                                    @error('stock_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image_img"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductImage') }}</label>

                                <div class="col-xl-6">
                                    <input id="image_img" type="file"
                                        class="form-control @error('image_img') is-invalid @enderror p-1" name="image_img[]"
                                        multiple required autocomplete="image_img" autofocus>

                                    @error('image_img')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row text-center">
                                <a href="{{ url()->previous() }}"
                                    class="btn btn-secondary col-sm-6 ml-auto">{{ __('text.CancelAction') }}</a>
                                <button type="submit"
                                    class="btn btn-primary col-sm-6 mr-auto" onclick="editortotextarea()">{{ __('text.ConfirmAction') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
$(document).ready(function() {
  $('#descriptionEditor').summernote({
        tabsize: 1,
        height: 100,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
});

function editortotextarea() { 
    var description = document.getElementById('description');
    description.value  = $('#descriptionEditor').summernote('code');
 }
    </script>
@endsection