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

    <form action="#" id="LCMs-form"
          method="POST">
        @csrf


        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">


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
                                        <input type="checkbox" name="browse" id="browse" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- CONTAINER FOR FIELDS -->
                        <div id="fields-container" class="mb-5"></div>
                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-primary"
                                    id="add-new-field"> {{__('alder::lcm.add_new_field')}}</button>
                        </div>


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

