@php
    $type = $field->type;
@endphp

<div class="field card shadow">

    <div class="card-header font-weight-bold text-primary">{{$field->display_name}}</div>
    <div class="field__delete">&times;</div>

    <div class="card-body row m-0">

        <!-- FIELD NAME -->
        <div class="col-xl-4 col-lg-6">
            <div class="mb-2">
                <label for="field_name">{{__('alder::inputs.field_name')}} *</label>
                <div class="input-group">
                    <input type="text" name="field_name" id="field_name"
                           class="form-control" value="{{$field->display_name}}" required>
                </div>
            </div>
        </div>

        <!-- TYPE -->
        <div class="col-xl-4 col-lg-6">
            <div class="mb-2">
                <label for="type"> {{__('alder::inputs.type')}} *</label>
                <select name="type" id="type" class="custom-select" required>

                    @php
                        $typeArray = array(
                                "text" => __('alder::inputs.text') ,
                                "relation" => __('alder::inputs.relation'),
                                "number" => __('alder::inputs.number'),
                                "repeater" => __('alder::inputs.repeater') ,
                                "select" => __('alder::inputs.select') ,
                                "select-multiple" =>__('alder::inputs.select_multiple'),
                                "checkbox" =>__('alder::inputs.checkbox'),
                                "radio" =>__('alder::inputs.radio'),
                                "password" =>__('alder::inputs.password'),
                                "file" =>__('alder::inputs.file'),
                                "file-multiple" =>__('alder::inputs.file_multiple'),
                                "date" =>__('alder::inputs.date'),
                                "datetime-local" =>__('alder::inputs.datetime_local'),
                                "time" =>__('alder::inputs.time'),
                                "month" =>__('alder::inputs.month'),
                                "color" =>__('alder::inputs.color')
                        );
                    @endphp

                    @foreach($typeArray as $type_k => $type_v)
                        <option value="{{$type_k}}" @php if($type_k === $type) echo "selected" @endphp>{{$type_v}}</option>
                    @endforeach;

                </select>
            </div>
        </div>


    @if($type === "repeater")
        <!-- REPEATER -->
            <div class="repeater-field-container" data-dependence="type:repeater">


                @foreach($field->fields as $subfield_k => $subfield)
                    @php $field = $subfield; $field_name = $subfield_k; @endphp
                    @include('alder::components.LCM')
                @endforeach


                <button type="button"
                        class="btn btn-sm btn-icon-split btn-primary add-new-field-repeater">
                        <span class="icon text-white-50">
                                <i class="fa fa-plus"></i>
                            </span>

                    <span class="text">{{__('alder::lcm.add_new_subfield')}}</span>
                </button>
            </div>
    @endif


    @if($type === "relation")
        <!-- RELATION TYPE -->
            <div class="col-xl-4 col-lg-6" data-dependence="type:relation">
                <div class="mb-2">
                    <label for="relation_type">{{__('alder::lcm.relation_type')}}</label>
                    <select name="relation_type" id="relation_type" class="custom-select">
                        @php
                            $relationTypesArray = array(
                                "belongsTo" => "belongsTo",
                                "belongsToMany" => "belongsToMany",
                            );
                        @endphp
                        @foreach($relationTypesArray as $relation_k => $relation_v)
                            <option value="{{$relation_k}}"
                                    @php if($relation_k === $field->relation_type) echo "selected" @endphp>{{$relation_v}}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <!-- *** LEAF TYPE *** -->
            <div class="col-xl-4 col-lg-6" data-dependence="type:relation">
                <div class="mb-2">
                    <label for="leaf_type">{{__('alder::lcm.leaf_type')}}</label>
                    <input type="text" name="leaf_type" id="leaf_type" class="form-control"
                           value="{{$field->leaf_type}}">
                </div>
            </div>
    @endif


    @if($type === "select" || $type === "select-multiple" || $type === "radio" || $type === "checkbox")
        <!-- *** OPTIONS *** -->
            <div class="col-xl-4 col-lg-6"
                 data-dependence="type:select select-multiple radio checkbox">
                <div class="mb-2">
                    <label for="options" class="mb-0">{{__('alder::lcm.options')}}</label>
                    <div><em>"key : value" ({{__('alder::lcm.each_opt_separate_line')}})</em></div>

                    @php
                        $options = "";
                        if(isset($field->options)):
                        foreach($field->options as $opt_k => $opt_v):
                            $options .= $opt_k ." : " . $opt_v . "\n";
                        endforeach;
                        endif;
                    @endphp

                    <textarea name="options" id="options" rows="4"
                              class="form-control">{{$options}}</textarea>
                </div>
            </div>
    @endif

    <!-- *** PANEL *** -->
        <div class="col-xl-4 col-lg-6">
            <div class="mb-2">
                <label for="panel">{{__('alder::lcm.panel')}}</label>
                <select name="panel" id="panel" class="custom-select">

                    @php
                        if(isset($field->panel))
                        $panel = $field->panel;
                    @endphp

                    <option value="left" @php if(isset($field->panel) && $panel === "left") echo "selected" @endphp >{{__('alder::lcm.left')}}</option>
                    <option value="right" @php if(isset($field->panel) && $panel === "right") echo "selected" @endphp >{{__('alder::lcm.right')}}</option>
                </select>
            </div>
        </div>

    @php $default = ""; if(isset($field->default)) $default = $field->default; @endphp
    <!-- *** DEFAULT *** -->
        <div class="col-xl-4 col-lg-6">
            <div class="mb-2">
                <label for="default">{{__('alder::lcm.default')}}</label>
                <input name="default" id="default" class="form-control" value="{{$default}}">
            </div>
        </div>


    @php $nullable = "fuck"; if(isset($field->nullable)) $nullable = $field->nullable; @endphp
    <!-- *** NULLABLE *** -->
        <div class="col-xl-4 col-lg-6">
            <div class="mb-2">
                <label for="nullable">{{__('alder::lcm.nullable')}}</label>
                <select name="nullable" id="nullable" class="custom-select">
                    <option value="true" @php if($nullable && $nullable !== "fuck") echo "selected" @endphp>{{__('alder::lcm.true')}}</option>
                    <option value="false" @php if(!$nullable) echo "selected" @endphp>{{__('alder::lcm.false')}}</option>
                </select>
            </div>
        </div>

        <!-- *** BROWSE *** -->
        <div class="col-xl-4 col-lg-6 d-flex align-items-center">
            <div class="mb-2">
                <label for="browse">{{__('alder::lcm.browse')}}</label>
                <input type="checkbox" name="browse" id="browse" class="icheck"
                        @php if(in_array($field_name, $browse)) echo "checked" @endphp>
            </div>
        </div>
    </div>
</div>