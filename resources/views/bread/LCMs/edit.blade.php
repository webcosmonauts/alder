@extends('alder::layouts.main')

@section('scripts-body')
    <link rel="stylesheet" href="{{asset('vendor/LCMs/css/LCMs.css')}}">
    <script src="{{asset('vendor/LCMs/js/LCMs.js')}}"></script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{$edit ? $LCM->title : __('alder::lcm.singular')}}</h1>
        @include('alder::components.locale-switcher')
    </div>

    <form action="{{$edit ? route('alder.LCMs.update', $LCM->slug) : route('alder.LCMs.store')}}" id="LCMs-form"
          method="POST"
          novalidate>
        @csrf

    @php
        if($edit) {
            $lcm = $LCM->modifiers->lcm;
            $conditions = $LCM->modifiers->conditions;
            $browse = $LCM->modifiers->bread->browse->table_columns;

            //dd($lcm);
        }
    @endphp

    <!-- *** LCM MAIN FIELDS *** -->
        <div class="card shadow mb-5">
            <div class="card-header font-weight-bold text-primary">LCM</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- LCM TITLE -->
                        <div class="mb-2">
                            <label for="lcm_title">{{__('alder::lcm.lcm_title')}} *</label>
                            <div class="input-group">
                                <input type="text" name="lcm_title" id="lcm_title"
                                       class="form-control" data-title="1" required
                                        @php if($edit) echo 'value="'.$LCM->title.'"' @endphp>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- LCM SlUG -->
                        <div class="mb-2">
                            <label for="lcm_slug">LCM slug *</label>
                            <div class="input-group">
                                <input type="text" name="lcm_slug" id="lcm_slug"
                                       class="form-control" data-slug="1" required
                                        @php if($edit) echo 'value="'.$LCM->slug.'"' @endphp>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- LCM GROUP TITLE -->
                        <div class="mb-2">
                            <label for="lcm_group_title">{{__('alder::lcm.group_title')}}</label>
                            <div class="input-group">
                                <input type="text" name="lcm_group_title" id="lcm_group_title"
                                       class="form-control" data-title="2"
                                        @php if($edit) echo 'value="'.$LCM->group_title.'"' @endphp>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- LCM SlUG -->
                        <div class="mb-2">
                            <label for="lcm_group_slug">{{__('alder::lcm.group_slug')}} </label>
                            <div class="input-group">
                                <input type="text" name="lcm_group_slug" id="lcm_group_slug"
                                       class="form-control" data-slug="2"
                                        @php if($edit) echo 'value="'.$LCM->group_slug.'"' @endphp>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- LCM GROUP TITLE -->
                        <div class="mb-2">
                            <label for="leaf_type_id">{{__('alder::leaf_types.singular')}}</label>
                            <div class="input-group">
                                <select class="custom-select" name="leaf_type_id" id="leaf_type_id">
                                    @foreach($leaf_types as $leaf_type)
                                        <option {{ $edit && ($leaf_type->id == $LCM->leaf_type_id) ? 'selected' : '' }}
                                                value="{{ $leaf_type->id }}">
                                            {{ __("alder::leaf_types.$leaf_type->slug.singular") }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- *** TABS *** -->
        <div class="lcm-tabs">
            <a href="#main" class="lcm-tabs__link active">
                <span>{{__('alder::lcm.main')}}</span>
            </a>

            @if($edit)
                @php $tabsCounter = 1; @endphp
                @foreach($lcm as $field_k => $field)

                    @if(!isset($field->type))
                        <a href="#section-{{$tabsCounter}}" class="lcm-tabs__link">{{$field->display_name}}
                            <em>&times;</em></a>
                    @endif
                    @php $tabsCounter++; @endphp
                @endforeach
            @endif

            <a href="#" id="add-new-tab"> <em class="fa fa-plus"></em> <span class="d-none">add new tab</span> </a>
            <input type="text" id="lcm-tab-edit">
        </div>

        <!-- *** TABS CONTENT *** -->
        <div class="lcm-tabs-content">
            <div class="content shadow mb-5 active" id="main">
                @if($edit)
                    @foreach($lcm as $field_k => $field)
                        @if(isset($field->type))
                            @php $field_name = $field_k; @endphp
                            @include('alder::components.LCM')
                        @endif
                    @endforeach
                @endif
            </div>

            @if($edit)
                @php $tabsCounter = 1; @endphp
                @foreach($lcm as $field_k => $field)
                    @if(!isset($field->type))
                        <div class="content shadow mb-5" id="section-{{$tabsCounter}}">
                            @foreach($field->fields as $subfield_k => $subfield)
                                @php $field = $subfield; $field_name = $subfield_k; @endphp
                                @include('alder::components.LCM')
                            @endforeach
                        </div>
                    @endif
                    @php $tabsCounter++; @endphp
                @endforeach
            @endif

            <button type="button" class="btn btn-primary btn-icon-split"
                    id="add-new-field">
                <span class="icon text-white-50">
                                <i class="fa fa-plus"></i>
                            </span>
                <span class="text">{{__('alder::lcm.add_new_field')}}</span></button>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold text-primary"> {{__('alder::lcm.conditions')}} </div>
                    <div class="card-body">
                        @if($edit && count($conditions) > 0)

                            @foreach($conditions as $condition)

                                @php
                                    $current_parameter = $condition->parameter;
                                    $current_operator = $condition->operator;
                                    $current_value  = $condition->value;

                                       $parameters = array(
                                           "page-template" => __('alder::lcm.page_template'),
                                           "leaf-type" => "LeafType"
                                       );

                                        $operators = array(
                                            "is" =>__('alder::lcm.is'),
                                            "not" =>__('alder::lcm.not')
                                        );
                                @endphp

                                <div class="condition-field d-flex flex-wrap">
                                    <div class="form-group">
                                        <label for="parameter"> </label>
                                        <select name="parameter" id="parameter" class="custom-select">
                                            @foreach($parameters as $parameter_k => $parameter_v)
                                                <option value="{{$parameter_k }}" @php if($parameter_k === $current_parameter) echo "selected"; @endphp> {{$parameter_v}} </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group form-group-small">
                                        <label for="operator"> </label>

                                        <select name="operator" id="operator" class="custom-select">
                                            @foreach($operators as $operator_k => $operator_v)
                                                <option value="{{$operator_k }}" @php if($operator_k === $current_operator) echo "selected"; @endphp> {{$operator_v}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="value"></label>
                                        @php
                                            $templates_object = TemplateHelper::getTemplatesObject("alder");
                                        @endphp


                                        <select name="value" id="value" class="custom-select">

                                            @foreach($templates_object as $name=>$single_template)
                                                <option data-group="page-template"
                                                        @php if($current_parameter !== "page-template") echo 'class="d-none"'; elseif($single_template['template_name'] == $current_value) echo "selected"; @endphp
                                                        value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
                                            @endforeach

                                            @foreach($leaf_types as $leaf_type)
                                                <option data-group="leaf-type"
                                                        @php if($current_parameter !== "leaf-type") echo 'class="d-none"'; elseif($leaf_type->id == $current_value) echo "selected"; @endphp
                                                        value="{{$leaf_type->id}}">{{$leaf_type->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="condition-field__delete">&times;</div>
                                </div>

                            @endforeach
                        @else

                            <div class="condition-field d-flex flex-wrap">
                                <div class="form-group">
                                    <label for="parameter"> </label>
                                    <select name="parameter" id="parameter" class="custom-select">
                                        <option value="page-template"> {{__('alder::lcm.page_template')}} </option>
                                        <option value="leaf-type">LeafType</option>
                                    </select>
                                </div>

                                <div class="form-group form-group-small">
                                    <label for="operator"> </label>

                                    <select name="operator" id="operator" class="custom-select">
                                        <option value="is">{{__('alder::lcm.is')}}</option>
                                        <option value="not">{{__('alder::lcm.not')}}</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label for="value"></label>

                                    @php
                                        $templates_object = TemplateHelper::getTemplatesObject("alder");
                                    @endphp


                                    <select name="value" id="value" class="custom-select">

                                        @foreach($templates_object as $name=>$single_template)
                                            <option data-group="page-template"
                                                    value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
                                        @endforeach

                                        @foreach($leaf_types as $leaf_type)
                                            <option class="d-none" data-group="leaf-type"
                                                    value="{{$leaf_type->id}}">{{$leaf_type->title}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="condition-field__delete">&times;</div>

                            </div>
                        @endif

                        <button id="add-new-condition" type="button"
                                class="btn btn-primary btn-icon-split mt-5">
                            <span class="icon text-white-50">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span
                                    class="text">{{__('alder::lcm.add_new_condition')}}</span></button>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <button type="submit" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-save"></i>
                </span>
                    <span class="text">{{ __('alder::generic.save') }}</span>
                </button>
            </div>
        </div>
    </form>

    <!-- CONFIRM TO DETELE TAB -->
    <div class="modal fade in" id="confirm-delete-tab" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('alder::lcm.are_you_sure_delete_tab')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="text-right">
                        <div class="btn btn-primary"> {{__('alder::lcm.yes')}} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONFIRM TO DETELE CONDITION -->
    <div class="modal fade in" id="confirm-delete-condition" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">{{__('alder::lcm.are_you_sure_delete_condition')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="text-right">

                        <div class="btn btn-primary"> {{__('alder::lcm.yes')}} </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ************************************************************ -->
    <!-- PATTERN -->
    <div id="field-pattern" hidden>
        <div class="field card shadow">
            <div class="card-header font-weight-bold text-primary"> {{__('alder::inputs.field')}}</div>
            <div class="field__delete">&times;</div>

            <div class="card-body row m-0">
                <!-- FIELD NAME -->
                <div class="col-xl-4 col-lg-6">
                    <div class="mb-2">
                        <label for="field_name">{{__('alder::inputs.field_name')}} *</label>
                        <div class="input-group">
                            <input type="text" name="field_name" id="field_name"
                                   class="form-control" disabled required>
                        </div>
                    </div>
                </div>

                <!-- TYPE -->
                <div class="col-xl-4 col-lg-6">
                    <div class="mb-2">
                        <label for="type"> {{__('alder::inputs.type')}} *</label>
                        <select name="type" id="type" class="custom-select" disabled required>
                            <option value="text"> {{__('alder::inputs.text')}} </option>
                            <option value="relation"> {{__('alder::inputs.relation')}}</option>
                            <option value="number"> {{__('alder::inputs.number')}}</option>
                            <option value="repeater"> {{__('alder::inputs.repeater')}} </option>
                            <option value="select"> {{__('alder::inputs.select')}} </option>
                            <option value="select-multiple">{{__('alder::inputs.select_multiple')}}</option>
                            <option value="checkbox">{{__('alder::inputs.checkbox')}}</option>
                            <option value="radio">{{__('alder::inputs.radio')}}</option>
                            <option value="password">{{__('alder::inputs.password')}}</option>
                            <option value="file">{{__('alder::inputs.file')}}</option>
                            <option value="file-multiple">{{__('alder::inputs.file_multiple')}}</option>
                            <option value="date">{{__('alder::inputs.date')}}</option>
                            <option value="datetime-local">{{__('alder::inputs.datetime_local')}}</option>
                            <option value="time">{{__('alder::inputs.time')}}</option>
                            <option value="month">{{__('alder::inputs.month')}}</option>
                            <option value="color">{{__('alder::inputs.color')}}</option>
                        </select>
                    </div>
                </div>

                <!-- REPEATER -->
                <div class="repeater-field-container" data-dependence="type:repeater" hidden>
                    <button type="button"
                            class="btn btn-sm btn-icon-split btn-primary add-new-field-repeater">
                        <span class="icon text-white-50">
                                <i class="fa fa-plus"></i>
                            </span>
                        <span class="text">{{__('alder::lcm.add_new_subfield')}}</span>
                    </button>
                </div>

                <!-- RELATION TYPE -->
                <div class="col-xl-4 col-lg-6" data-dependence="type:relation" hidden>
                    <div class="mb-2">

                        <label for="relation_type">{{__('alder::inputs.relation_type')}}</label>
                        <select name="relation_type" id="relation_type" class="custom-select"
                                disabled>
                            <option value="belongsTo">belongsTo</option>
                            <option value="belongsToMany">belongsToMany</option>
                        </select>
                    </div>
                </div>

                <!-- *** LEAF TYPE *** -->
                <div class="col-xl-4 col-lg-6" data-dependence="type:relation" hidden>
                    <div class="mb-2">
                        <label for="leaf_type">{{__('alder::lcm.leaf_type')}}</label>
                        <input type="text" name="leaf_type" id="leaf_type" class="form-control"
                               disabled>
                    </div>
                </div>

                <!-- *** OPTIONS *** -->
                <div class="col-xl-4 col-lg-6"
                     data-dependence="type:select select-multiple radio checkbox" hidden>
                    <div class="mb-2">
                        <label for="options" class="mb-0">{{__('alder::inputs.options')}}</label>
                        <div><em>"key : value" ({{__('alder::lcm.each_opt_separate_line')}})</em></div>
                        <textarea name="options" id="options" rows="4" disabled
                                  class="form-control"></textarea>
                    </div>
                </div>

                <!-- *** PANEL *** -->
                <div class="col-xl-4 col-lg-6">
                    <div class="mb-2">
                        <label for="panel">{{__('alder::lcm.panel')}}</label>
                        <select name="panel" id="panel" class="custom-select" disabled>
                            <option value="left">{{__('alder::lcm.left')}}</option>
                            <option value="right">{{__('alder::lcm.right')}}</option>
                        </select>
                    </div>
                </div>

                <!-- *** DEFAULT *** -->
                <div class="col-xl-4 col-lg-6">
                    <div class="mb-2">
                        <label for="default">{{__('alder::lcm.default')}}</label>
                        <input name="default" id="default" class="form-control" disabled>
                    </div>
                </div>

                <!-- *** NULLABLE *** -->
                <div class="col-xl-4 col-lg-6">
                    <div class="mb-2">
                        <label for="nullable">{{__('alder::lcm.nullable')}}</label>
                        <select name="nullable" id="nullable" class="custom-select" disabled>
                            <option value="true">{{__('alder::lcm.true')}}</option>
                            <option value="false">{{__('alder::lcm.false')}}</option>
                        </select>
                    </div>
                </div>

                <!-- *** BROWSE *** -->
                <div class="col-xl-4 col-lg-6 d-flex align-items-center">
                    <div class="mb-2">
                        <label for="browse">{{__('alder::lcm.browse')}}</label>
                        <input type="checkbox" name="browse" id="browse" disabled>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

