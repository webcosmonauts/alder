@extends('alder::layouts.main')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ Str::title(str_replace('-', ' ', $leaf_type->name)) }}</h1>
    </div>

    <form action="{{ route("alder.$leaf_type->name.store") }}" method="POST">
        @csrf


        @php

            $right_panel_count = 0;
            if(isset($params->fields)):

            foreach($params->fields as $field_name => $field_modifiers) :
                if(isset($field_modifiers->panel)) :
                    if($field_modifiers->panel == 'right') :
                        $right_panel_count++;
                    endif;
                endif;
            endforeach;
            endif;

        @endphp

        <div class="row">
            <div class="col-lg-@php if($right_panel_count > 0)  echo '9'; else echo '12'; @endphp">
                <div class="card shadow mb-4">
                    <div class="card-body">

                        @foreach(['title', 'slug', 'user_id'] as $field)

                            <label for="{{ $field }}">{{ $field }}</label>
                            <div class="input-group mb-4">
                                <input type="text" name="{{ $field }}" id="{{ $field }}" class="form-control"
                                       placeholder="{{ $field }}"
                                       aria-label="{{ $field }}" aria-describedby="{{ $field }}">
                            </div>
                        @endforeach

                        <label for="#content">Content</label>
                        <div class="input-group mb-4">
                    <textarea type="text" rows="8" name="content" id="content" class="form-control"
                              aria-label="content" aria-describedby="content" placeholder="Content"></textarea>
                        </div>

                        @if(isset($params->fields))


                            @foreach($params->fields as $field_name => $field_modifiers)
                            <!--
                                 @if($field_modifiers->type == 'relation')
                                    @switch($field_modifiers->relation_type)
                                        @case('hasOne')
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <label class="input-group-text" for="{{ $field_name }}">{{ $field_name }}</label>
                                    </div>
                                    <select class="custom-select" id="{{ $field_name }}">
                                        <option >Choose...</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                @break
                                        @case('hasMany')
                                        @break
                                        @case('belongsTo')
                                        @break
                                        @case('belongsToMany')
                                        @break
                                        @default
                                    @endswitch
                                @else

                                @endif
                                    -->


                                @if(!isset($field_modifiers->panel) || $field_modifiers->panel != 'right')

                                    @include('alder::components.input')

                                @endif


                            @endforeach
                        @endif

                        <button type="submit" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                      <i class="fas fa-save"></i>
                    </span>
                            <span class="text">{{ __('alder::generic.save') }}</span>
                        </button>
                    </div>
                </div>
            </div>


        @if($right_panel_count > 0)

            <!-- SIDEBAR -->
                <div class="col-lg-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            @foreach($params->fields as $field_name => $field_modifiers)
                                @if(isset($field_modifiers->panel) && $field_modifiers->panel == 'right')

                                    @include('alder::components.input')

                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
        </div>
        @endif
    </form>

@endsection
