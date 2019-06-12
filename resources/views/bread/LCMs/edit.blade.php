@extends('alder::layouts.main')


@section('scripts-body')

    <link rel="stylesheet" href="{{asset('vendor/LCMs/css/LCMs.css')}}">
    <script src="{{asset('vendor/LCMs/js/LCMs.js')}}"></script>
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{__('alder::lcm.singular')}}</h1>
    </div>

    <form action="#" id="LCMs-form" method="POST" novalidate>
        @csrf


        <!-- *** LCM MAIN FIELDS *** -->
        <div class="card shadow mb-5">
            <div class="card-header font-weight-bold text-primary">LCM</div>
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <!-- LCM TITLE -->
                        <div class="form-group">
                            <label for="lcm_title">{{__('alder::lcm.lcm_title')}} *</label>
                            <div class="input-group">
                                <input type="text" name="lcm_title" id="lcm_title"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- LCM SlUG -->
                        <div class="form-group">
                            <label for="lcm_slug">LCM slug *</label>
                            <div class="input-group">
                                <input type="text" name="lcm_slug" id="lcm_slug"
                                       class="form-control" required>
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


            <a href="#" id="add-new-tab"> <em class="fa fa-plus"></em> <span class="d-none">add new tab</span> </a>
            <input type="text" id="lcm-tab-edit">
        </div>


        <!-- *** TABS CONTENT *** -->
        <div class="lcm-tabs-content">
            <div class="content mb-5 active" id="main">
            </div>


            <button type="button" class="btn btn-primary"
                    id="add-new-field"> {{__('alder::lcm.add_new_field')}}</button>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header font-weight-bold text-primary"> {{__('alder::lcm.conditions')}} </div>

                    <div class="card-body">


                        <div class="conditional-field d-flex flex-wrap">

                            <div class="form-group">
                                <label for="parameter"> </label>
                                <select name="parameter" id="parameter" class="form-control">
                                    <option value="page-template"> {{__('alder::lcm.page_template')}} </option>
                                    <option value="leaf-type">LeafType</option>
                                </select>
                            </div>

                            <div class="form-group form-group-small">
                                <label for="operator"> </label>

                                <select name="operator" id="operator" class="form-control">
                                    <option value="is">{{__('alder::lcm.is')}}</option>
                                    <option value="not">{{__('alder::lcm.not')}}</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="value"> </label>

                                @php
                                    $templates_object = TemplateHelper::getTemplatesObject("alder");
                                @endphp

                                <select name="value" id="value" class="custom-select">

                                    @foreach($templates_object as $name=>$single_template)
                                        <option data-group="page-template"
                                                value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
                                    @endforeach

                                    <option class="d-none" data-group="leaf-type" value="1">lorem1</option>
                                    <option class="d-none" data-group="leaf-type" value="2">lorem2</option>
                                </select>

                            </div>
                        </div>


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


    <!-- CONFIRM DETELE TAB -->
    <div class="modal fade in" id="confirm-delete-tab" tabindex="-1" role="dialog"
         aria-labelledby="mySmallModalLabel"
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

    <!-- PATTERN -->
    <div id="field-pattern" hidden>
        <div class="field card shadow">

            <div class="card-header font-weight-bold text-primary"> {{__('alder::lcm.field')}}</div>
            <div class="field__delete">&times;</div>

            <div class="card-body row m-0">

                <!-- FIELD NAME -->
                <div class="form-group">
                    <label for="field_name">{{__('alder::lcm.field_name')}} *</label>
                    <div class="input-group">
                        <input type="text" name="field_name" id="field_name"
                               class="form-control" disabled required>
                    </div>
                </div>

                <!-- TYPE -->
                <div class="form-group">
                    <label for="type"> {{__('alder::lcm.type')}} *</label>
                    <select name="type" id="type" class="form-control" disabled required>
                        <option value="text"> {{__('alder::lcm.text')}} </option>
                        <option value="relation"> {{__('alder::lcm.relation')}}</option>
                        <option value="number"> {{__('alder::lcm.number')}}</option>
                        <option value="select"> {{__('alder::lcm.select')}} </option>
                        <option value="select-multiple">{{__('alder::lcm.select_multiple')}}</option>
                        <option value="checkbox">{{__('alder::lcm.checkbox')}}</option>
                        <option value="radio">{{__('alder::lcm.radio')}}</option>
                        <option value="password">{{__('alder::lcm.password')}}</option>
                        <option value="file">{{__('alder::lcm.file')}}</option>
                        <option value="file-multiple">{{__('alder::lcm.file_multiple')}}</option>
                        <option value="date">{{__('alder::lcm.date')}}</option>
                        <option value="datetime-local">{{__('alder::lcm.datetime_local')}}</option>
                        <option value="time">{{__('alder::lcm.time')}}</option>
                        <option value="month">{{__('alder::lcm.month')}}</option>
                        <option value="color">{{__('alder::lcm.color')}}</option>
                    </select>
                </div>


                <!-- RELATION TYPE -->
                <div class="form-group" data-dependence="type:relation" hidden>
                    <label for="relation_type">{{__('alder::lcm.relation_type')}}</label>
                    <select name="relation_type" id="relation_type" class="form-control"
                            disabled>
                        <option value="hasOne">hasOne</option>
                        <option value="hasMany">hasMany</option>
                        <option value="belongsTo">belongsTo</option>
                        <option value="belongsToMany">belongsToMany</option>
                    </select>
                </div>


                <!-- *** LEAF TYPE *** -->
                <div class="form-group" data-dependence="type:relation" hidden>
                    <label for="leaf_type">{{__('alder::lcm.leaf_type')}}</label>
                    <input type="text" name="leaf_type" id="leaf_type" class="form-control"
                           disabled>
                </div>


                <!-- *** OPTIONS *** -->
                <div class="form-group"
                     data-dependence="type:select select-multiple radio checkbox" hidden>
                    <label for="options" class="mb-0">{{__('alder::lcm.options')}}</label>
                    <div><em>"key : value" ({{__('alder::lcm.each_opt_separate_line')}})</em></div>

                    <textarea name="options" id="options" rows="4" disabled
                              class="form-control"></textarea>
                </div>


                <!-- *** PANEL *** -->
                <div class="form-group">
                    <label for="panel">{{__('alder::lcm.panel')}}</label>
                    <select name="panel" id="panel" class="form-control" disabled>
                        <option value="left">{{__('alder::lcm.left')}}</option>
                        <option value="right">{{__('alder::lcm.right')}}</option>
                    </select>
                </div>

                <!-- *** DEFAULT *** -->
                <div class="form-group">
                    <label for="default">{{__('alder::lcm.default')}}</label>
                    <input name="default" id="default" class="form-control" disabled>
                </div>


                <!-- *** NULLABLE *** -->
                <div class="form-group">
                    <label for="nullable">{{__('alder::lcm.nullable')}}</label>
                    <select name="nullable" id="nullable" class="form-control" disabled>
                        <option value="true">{{__('alder::lcm.true')}}</option>
                        <option value="false">{{__('alder::lcm.false')}}</option>
                    </select>
                </div>

                <!-- *** BROWSE *** -->
                <div class="form-group d-flex align-items-center">
                    <label for="browse">{{__('alder::lcm.browse')}}</label>
                    <input type="checkbox" name="browse" id="browse" class="icheck" disabled>
                </div>
            </div>
        </div>
    </div>

@endsection

