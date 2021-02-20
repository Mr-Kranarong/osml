@extends('layouts.app')

@section('content')

@auth
{{-- ========================================== ADMIN ========================================== --}}
@if (Auth::user()->hasAccess())
<div class="container">
    <div class="row justify-content-center">
        {{-- ORDER --}}
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{__('text.Settings')}}
                </div>
                <div class="card-body">
                    <input type="text" id="searchInput" onkeyup="searchFunction()" placeholder="{{__('text.SearchOption')}}">

                    <div class="table-responsive">
                        <table id="optionTable" class="table table-striped table-hover table-sm table-bordered">
                        <tr class="header thead-dark">
                            <th style="width:40%;">{{__('text.Option')}}</th>
                            <th style="width:55%;">{{__('text.Value')}}</th>
                            <th style="width:5%;"></th>
                        </tr>
                        @foreach ($settings as $setting)
                        <tr>
                            <td>{{$setting->option}}</td>
                            <td class="text-break">{{$setting->value}}</td>
                            <td class="text-center"><a href="#" data-backdrop="static" data-keyboard="false" data-toggle="modal" data-target="#option-modal-update-{{ $setting->id }}"><i class="fas fa-pencil-alt text-dark"></i></a></td>
                            {{-- OPTION UPDATE MODAL --}}
                            <div class="modal fade" id="option-modal-update-{{ $setting->id }}" tabindex="-1" role="dialog" aria-labelledby="option-modal-update-label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="option-modal-update-label-{{ $setting->id }}">{{__('text.UpdateOption')}}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="document.getElementById('form-option-update-{{ $setting->id }}').reset()">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                <form action="{{route('settings.update',$setting)}}" method="POST" id="form-option-update-{{ $setting->id }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-body">

                                            <div class="form-group row">
                                                <label for="value" class="col-12 col-form-label">{{$setting->option}}</label>
                                                <div class="col-12">
                                                    <textarea id="value-{{ $setting->id }}" type="text" class="form-control @error('name') is-invalid @enderror" name="value" required autocomplete="value" autofocus rows="5">{{$setting->value}}</textarea>

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
                        </tr>
                        @endforeach
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ========================================== ADMIN ========================================== --}}
@endauth

@endsection

@section('script')
<script>
    function searchFunction() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("searchInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("optionTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
    </script>
@endsection
