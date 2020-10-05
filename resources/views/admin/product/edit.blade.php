@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
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
                    <div class="card-header">{{ __('text.EditProduct') }}</div>

                    <div class="card-body">
                        <form action="{{ route('product.update', $product) }}" method="POST" id="form-product-create"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-group row">
                                <label for="name"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductName') }}</label>

                                <div class="col-xl-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ $product->name }}" required autocomplete="name" autofocus>
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
                                        autocomplete="description" autofocus>{{ $product->description }}</textarea>

                                    @error('description')
                                    <span class=" invalid-feedback" role="alert">
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
                                            <option value="{{ $category->id }}" @if ($category->id == $product->category_id)
                                                selected
                                                @endif>{{ $category->name }}</option>
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
                                        class="form-control @error('price') is-invalid @enderror" name="price"
                                        value="{{ $product->price }}" required autocomplete="price" autofocus>

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
                                        value="{{ $product->stock_amount }}" required autocomplete="stock_amount" autofocus>

                                    @error('stock_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row d-flex flex-row flex-wrap">
                                @if ($product->image_img)
                                    <?php $images = explode('|', $product->image_img);$x=0; ?>
                                    @foreach ($images as $image)
                                    <?php $x++;?>
                                        <div class="mein-card">
                                            <a href="{{ url('images/' . $image) }}" class="venobox" data-gall="images"><img
                                                    src="{{ url('images/' . $image) }}"></a>
                                            <button type="button" class="mein-card-delete btn-danger" onclick="ExecutingOrderSixtySix(this,<?=$x?>)"><i
                                                    class="fas fa-times"></i></button>
                                            <input type="hidden" id="image-<?=$x?>" value="{{$image}}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="image_img"
                                    class="col-xl-4 col-form-label text-xl-right">{{ __('text.ProductImage') }}</label>

                                <div class="col-xl-6">
                                    <input id="image_img" type="file"
                                        class="form-control @error('image_img') is-invalid @enderror p-1" name="image_img[]"
                                        multiple autocomplete="image_img" autofocus>

                                    @error('image_img')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row text-center">
                                <a href="{{ route('product.index') }}"
                                    class="btn btn-secondary col-xs-6 ml-auto">{{ __('text.Back') }}</a>
                                <button type="submit"
                                    class="btn btn-primary col-xs-6 mr-auto" onclick="editortotextarea()">{{ __('text.ConfirmAction') }}</button>
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
        $('.venobox').venobox({
            infinigall: true,
        });
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
      }).summernote('code','{!! $product->description !!}');
    });

    function ExecutingOrderSixtySix(e,id){
        var targetImage = document.getElementById('image-'+id).value;
        $.ajax({
               type:'delete',
               url:"{{route('product.delete_image',$product)}}",
               data:{"_token": "{{ csrf_token() }}",'target_image': targetImage},
               success:function(data) {
                  e.parentElement.remove();
               }
            });
    }


function editortotextarea() { 
    var description = document.getElementById('description');
    description.value  = $('#descriptionEditor').summernote('code');
 }
</script>
@endsection
