@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1>{{ __("alder::leaf_types.profile.singular") }}</h1>
    </div>



    <form action="{{ route("alder.profile.update",  $user->id)}}" method="post">
        {{method_field('PUT')}}
        @csrf


        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        @foreach(['name', 'surname', 'email', 'password', 'is_active'] as $field)
                            <label for="{{ $field }}">{{ $field }}</label>
                            <div class="input-group mb-4">
                                <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                       placeholder="{{ $field}}"
                                       aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                       value="{{ $user ? (($field == 'password') ? '' : $user->$field) : '' }}">

                            </div>
                        @endforeach
                            <br>


                            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
                            <label for="userfile">{{ __("alder::leaf_types.profile.avatar") }}</label>
                            <input class="form-control-file" name="userfile" type="file" />
                            <br>
                            <br>


                            <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50">
                              <i class="fas fa-save"></i>
                            </span>
                            <span class="text">{{ __('alder::generic.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
