@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header row">
                    <div class="col-12">
                        {{__('text.CategoryManagement')}} ({{$total}})
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped tm-table-striped-even">
                            <colgroup>
                                <col span="1" style="width: 80%;"><!-- NAME -->
                                <col span="1" style="width: 10%;"><!-- UPDATE BUTTON -->
                                <col span="1" style="width: 10%;"><!-- DELETE BUTTON -->
                             </colgroup>
                            <thead>
                                <tr class="tm-bg-gray">
                                    <th scope="col">{{__('text.CategoryName')}}</th>
                                    <th scope="col"></th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td class="tm-product-name">{{$category->name}}</td>

                                    <td><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#category-modal-{{$category->id}}"><i class="fas fa-pencil-alt text-dark"></i></a></td>

                                    <td><a href="{{route('product.category.delete',$category)}}" onclick="event.preventDefault();document.getElementById('category-delete-{{$category->id}}').submit()"><i class="fas fa-trash-alt text-dark"></i></a></td>

                                    {{-- HIDDEN FORM FOR DELETE BUTTON --}}
                                    <form id="category-delete-{{$category->id}}" action="{{route('product.category.delete',$category)}}"
                                        method="POST" style="display: none;">
                                        @csrf
                                        @method('delete')
                                    </form>

                                    {{-- HIDDEN MODAL FOR UPDATE BUTTON --}}
                                    <div class="modal fade" id="category-modal-{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="category-modal-{{$category->id}}-label" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="category-modal-{{$category->id}}-label">{{__('text.EditingCategory')}}: {{$category->id}}</h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('form-category-{{$category->id}}').reset()">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                        <form action="{{route('product.category.update',$category)}}" method="POST" id="form-category-{{$category->id}}">
                                                @csrf
                                                @method('put')

                                                <div class="modal-body">

                                                    <div class="form-group row">
                                                        <label for="name" class="col-md-5 col-form-label text-md-right">{{ __('text.CategoryName') }}</label>

                                                        <div class="col-md-7">
                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $category->name }}" required autocomplete="name" autofocus>

                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reset()">{{__('text.CancelChanges')}}</button>
                                                    <button type="submit" class="btn btn-primary">{{__('text.SaveChanges')}}</button>
                                                </div>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{$categories->links()}}
                    </div>

                    <div class="form-group row mb-0">
                        <a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#category-modal-create" class="btn btn-sm btn-outline-success col-12">
                            {{ __('text.CreateCategory') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CATEGORY ADD MODAL --}}
<div class="modal fade" id="category-modal-create" tabindex="-1" role="dialog" aria-labelledby="category-modal-create-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="category-modal-create-label">{{__('text.CreateCategory')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('form-category-create').reset()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
    <form action="{{route('product.category.create')}}" method="POST" id="form-category-create">
            @csrf

            <div class="modal-body">

                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('text.CategoryName') }}</label>
                    <div class="col-md-7">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="reset()">{{__('text.CancelAction')}}</button>
                <button type="submit" class="btn btn-primary">{{__('text.ConfirmAction')}}</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
