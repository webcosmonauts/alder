@extends('alder::layouts.main')


@section('scripts-body')
    <link rel="stylesheet" href="{{asset('js/themes/snow.css')}}">
    <script src="{{asset('js/quill.min.js')}}"></script>
    <script src="{{asset('js/content-quill.js')}}"></script>

    <!-- LCM picker -->
    <script src="{{asset('vendor/LCM-picker/LCM-picker.js')}}"></script>
    <!-- LCM switcher -->
    <script src="{{asset('vendor/LCM-switcher/LCM-switcher.js')}}"></script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            {{ $edit ? __('alder::generic.edit') : __('alder::generic.add_new') }}
            {{ lcfirst(__("alder::leaf_types.$leaf_type->slug.singular")) }}
        </h1>
        @include('alder::components.locale-switcher')
    </div>

    <form id="edit-form"
          action="{{ $edit ? route("alder.$leaf_type->slug.update", $leaf->id) : route("alder.$leaf_type->slug.store") }}"
          enctype="multipart/form-data"
          method="POST">
        @csrf

        <input type="hidden" name="lcm" id="lcm">
        <div hidden id="this-leaf-type">{{$leaf_type->slug}}</div>

    {{$edit ? method_field('PUT') : method_field('POST')}}

    @php
        $mainRightPanelCounter = 0;

           foreach($params as $lcm_group) :
               $lcm = $lcm_group->lcm;

               foreach($lcm as $lcm_item => $k):
                    if(isset($k->panel) && $k->panel === 'right') $mainRightPanelCounter++;
               endforeach;

           endforeach;;
    @endphp


    <!-- tabs -->
        <div class="card card-nav-tabs card-plain">
            <div class="card-header card-header-danger">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul class="nav nav-tabs position-relative border-bottom-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="main-section-tab" data-toggle="tab" href="#main-section"
                                   role="tab">{{__('alder::lcm.main')}}</a>
                            </li>

                            @php $tabsCounter = 0; @endphp
                            @foreach($params as $lcm_group)
                                @php
                                    $lcm = $lcm_group->lcm;
                                    $conditions = $lcm_group->conditions;
                                    $conditions_str = "";

                                    foreach($conditions as $cond_item):
                                        $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                    endforeach;
                                @endphp


                                @foreach($lcm as $lcm_item_k => $lcm_item_v)
                                    @if(isset($lcm_item_v->fields))
                                        @php $tabsCounter++; @endphp

                                        <li class="nav-item" data-condition="{{$conditions_str}}" hidden>
                                            <a class="nav-link" id="section-{{$tabsCounter}}-tab" data-toggle="tab"
                                               data-tab-name="{{$lcm_item_k}}"
                                               href="#section-{{$tabsCounter}}"
                                               role="tab">{{$lcm_item_v->display_name}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- tabs content -->
        <div class="tab-content mb-5" id="myTabContent">

            <!-- *** MAIN TAB *** -->
            <div class="tab-pane fade card shadow show active" id="main-section" role="tabpanel">
                <div class="row">
                    <div class="col-lg-{{ $mainRightPanelCounter > 0 ? '9' : '12' }} mb-4 ">
                        <div class="card-body">
                            @foreach(['title', 'slug'] as $field)
                                <label for="{{ $field }}">{{ __("alder::table_columns.$field") }}</label>
                                <div class="input-group mb-2">
                                    <input type="text" name="{{ $field}}" id="{{ $field }}" class="form-control"
                                           placeholder="{{ __("alder::table_columns.$field") }}"
                                           data-{{$field}}="1"
                                           aria-label="{{ $field }}" aria-describedby="{{ $field }}"
                                           value="{{ $edit ? $leaf->$field : '' }}">
                                </div>
                            @endforeach
                            <label for="status_id">{{ __('alder::leaf_statuses.singular') }}</label>
                            <div class="input-group mb-2">
                                <select name="status_id" id="status_id" class="form-control"
                                        aria-label="{{ __('alder::leaf_statuses.singular') }}"
                                        aria-describedby="{{ __('alder::leaf_statuses.singular') }}">
                                    @php
                                        $current_status = ($edit && (isset($leaf->status_id)))
                                            ? $leaf->status_id
                                            : \Webcosmonauts\Alder\Models\LeafStatus::where('slug', 'published')->value('id');
                                    @endphp
                                    @foreach($relations->statuses as $status)
                                        <option {{ $status->id == $current_status ? 'selected' : '' }}
                                                value="{{ $status->id }}">
                                            {{ __("alder::leaf_statuses.$status->slug") }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="updated_at">{{ __("alder::table_columns.updated_at") }}</label>
                            <div class="input-group mb-2">
                                <input type="datetime-local" name="updated_at" id="updated_at" class="form-control"
                                       placeholder="{{ __("alder::table_columns.updated_at") }}"
                                       data-created_at="1"
                                       value="{{ $edit ? $leaf->updated_at_for_input : '' }}">
                            </div>

                            <label for="#content">{{__('alder::generic.content')}}</label>
                            <div class="input-group mb-2">

                                <div id="quill"
                                     style="height: 400px; width: 100%">@if($edit) {!! $leaf->content !!} @endif</div>
                                <textarea type="text" rows="8" hidden name="content" id="content"
                                          class="form-control"
                                          aria-label="content" aria-describedby="content"
                                          placeholder="Content"></textarea>
                            </div>


                            @foreach($params as $lcm_group)

                                @php
                                    $lcm = $lcm_group->lcm;
                                    $conditions = $lcm_group->conditions;
                                    $conditions_str = "";

                                    foreach($conditions as $cond_item):
                                        $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                    endforeach;
                                @endphp

                                @foreach($lcm as $lcm_item => $k)
                                    @php
                                        $field_name = $lcm_item;
                                        $label = $k->display_name;
                                        $field = $k;

                                        $field_value = "";

                                        if($edit && isset($leaf[$field_name]))
                                            $field_value = $leaf[$field_name];
                                    @endphp

                                    @if(isset($k->type))

                                        @php $type = $k->type; @endphp

                                        @if(!isset($k->panel) || $k->panel === "left")
                                            @include('alder::components.input')
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                @if($mainRightPanelCounter > 0)
                    <!-- SIDEBAR -->
                        <div class="col-lg-3 mb-4">
                            <div class="card-body">


                                @foreach($params as $lcm_group)
                                    @php
                                        $lcm = $lcm_group->lcm;
                                        $conditions = $lcm_group->conditions;
                                        $conditions_str = "";

                                        foreach($conditions as $cond_item):
                                                $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                                        endforeach;
                                    @endphp



                                    @foreach($lcm as $lcm_item => $k)
                                        @php
                                            $field_name = $lcm_item;
                                            $label = $k->display_name;
                                            $field = $k;

                                            $field_value = "";

                                            if($edit && isset($leaf[$field_name]))
                                                $field_value = $leaf[$field_name];
                                        @endphp

                                        @if(isset($k->type) && isset($k->panel) && $k->panel === "right")
                                            @php $type = $k->type; @endphp
                                            @include('alder::components.input')
                                        @endif
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- *** END MAIN TAB *** -->


            <!-- *** OTHER TABS **** -->
            @php $tabsCounter = 0; @endphp


            @foreach($params as $lcm_group)

                @php
                    $lcm = $lcm_group->lcm;
                    $conditions = $lcm_group->conditions;
                    $conditions_str = "";

                    foreach($conditions as $cond_item):
                            $conditions_str .= $cond_item->parameter . ":" . $cond_item->operator . ":" . $cond_item->value . " ";
                    endforeach;
                @endphp

                @foreach($lcm as $tab => $lcm_item)

                    @if(isset($lcm_item->fields))

                        @php $tabsCounter++; @endphp
                        <div class="tab-pane card shadow fade show" data-condition="{{$conditions_str}}" hidden
                             id="section-{{$tabsCounter}}" role="tabpanel">

                            @php
                                $rightPanelCounter = 0;
                                foreach ($lcm_item->fields as $lcm_subitem => $k):
                                  if(isset($k->panel) && $k->panel === "right") $rightPanelCounter++;
                                endforeach;
                            @endphp

                            <div class="row">
                                <div class="col-lg-{{ $rightPanelCounter > 0 ? '9' : '12' }} mb-4">
                                    <div class="card-body">

                                        @foreach($lcm_item->fields as $lcm_subitem => $k)
                                            @php
                                                $field_name = $lcm_subitem;
                                                $label = $k->display_name;
                                                $field = $k;

                                                $field_value = "";
                                                if($edit && isset($leaf[$tab]))
                                                    $field_value = $leaf[$tab]->$field_name;
                                            @endphp

                                            @if(isset($k->type))
                                                @php $type = $k->type; @endphp
                                                @if(!isset($k->panel) || $k->panel === "left")
                                                    @include('alder::components.input')
                                                @endif
                                            @endif
                                        @endforeach

                                    </div>
                                </div>

                            @if($rightPanelCounter > 0)
                                <!-- SIDEBAR -->
                                    <div class="col-lg-3 mb-4">
                                        <div class="card-body">
                                            @foreach($lcm_item->fields as $lcm_subitem => $k)
                                                @php
                                                    $field_name = $lcm_subitem;
                                                    $label = $k->display_name;
                                                    $field = $k;

                                                    $field_value = "";
                                                    if($edit && isset($leaf[$tab]))
                                                        $field_value = $leaf[$tab]->$field_name;
                                                @endphp

                                                @if(isset($k->type) && isset($k->panel) && $k->panel === "right")
                                                    @php $type = $k->type; @endphp
                                                    @include('alder::components.input')
                                                @endif
                                            @endforeach
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                @endif
            @endforeach
        @endforeach
        <!--  -->
        </div>


        <button type="submit" class="btn btn-success btn-icon-split mb-1">
                                        <span class="icon text-white-50">
                                          <i class="fas fa-save"></i>
                                        </span>
            <span class="text">{{ __('alder::generic.save') }}</span>
        </button>


    </form>
@endsection

