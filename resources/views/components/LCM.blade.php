@php
    $type = $field->type;
@endphp

<div class="field card shadow">

    <div class="card-header font-weight-bold text-primary"> {{__('alder::lcm.field')}}</div>
    <div class="field__delete">&times;</div>

    <div class="card-body row m-0">

        <!-- FIELD NAME -->
        <div class="form-group">
            <label for="field_name">{{__('alder::lcm.field_name')}} *</label>
            <div class="input-group">
                <input type="text" name="field_name" id="field_name"
                       class="form-control" value="{{$field->display_name}}" required>
            </div>
        </div>

        <!-- TYPE -->
        <div class="form-group">
            <label for="type"> {{__('alder::lcm.type')}} *</label>
            <select name="type" id="type" class="form-control" required>

                @php
                    $typeArray = array(
                            "text" => __('alder::lcm.text') ,
                            "relation" => __('alder::lcm.relation'),
                            "number" => __('alder::lcm.number'),
                            "repeater" => __('alder::lcm.repeater') ,
                            "select" => __('alder::lcm.select') ,
                            "select-multiple" =>__('alder::lcm.select_multiple'),
                            "checkbox" =>__('alder::lcm.checkbox'),
                            "radio" =>__('alder::lcm.radio'),
                            "password" =>__('alder::lcm.password'),
                            "file" =>__('alder::lcm.file'),
                            "file-multiple" =>__('alder::lcm.file_multiple'),
                            "date" =>__('alder::lcm.date'),
                            "datetime-local" =>__('alder::lcm.datetime_local'),
                            "time" =>__('alder::lcm.time'),
                            "month" =>__('alder::lcm.month'),
                            "color" =>__('alder::lcm.color')
                    );
                @endphp

                @foreach($typeArray as $type_k => $type_v)
                    <option value="{{$type_k}}" @php if($type_k === $type) echo "selected" @endphp>{{$type_v}}</option>
                @endforeach;

            </select>
        </div>


    @if($type === "repeater")
        <!-- REPEATER -->
            <div class="repeater-field-container" data-dependence="type:repeater">


                @foreach($field->fields as $subfield_k => $subfield)
                    @php $field = $subfield @endphp
                    @include('alder::components.LCM')
                @endforeach


                <button type="button" href="#"
                        class="btn btn-sm btn-primary add-new-field-repeater"> {{__('alder::lcm.add_new_field')}} </button>
            </div>
    @endif


    @if($type === "relation")
        <!-- RELATION TYPE -->
            <div class="form-group" data-dependence="type:relation">
                <label for="relation_type">{{__('alder::lcm.relation_type')}}</label>
                <select name="relation_type" id="relation_type" class="form-control">
                    @php
                        $relationTypesArray = array(
                            "hasOne" => "hasOne",
                            "hasMany" => "hasMany",
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



            <!-- *** LEAF TYPE *** -->
            <div class="form-group" data-dependence="type:relation">
                <label for="leaf_type">{{__('alder::lcm.leaf_type')}}</label>
                <input type="text" name="leaf_type" id="leaf_type" class="form-control" value="{{$field->leaf_type}}">
            </div>
    @endif


    @if($type === "select" || $type === "select-multiple" || $type === "radio" || $type === "checkbox")
        <!-- *** OPTIONS *** -->
            <div class="form-group"
                 data-dependence="type:select select-multiple radio checkbox">
                <label for="options" class="mb-0">{{__('alder::lcm.options')}}</label>
                <div><em>"key : value" ({{__('alder::lcm.each_opt_separate_line')}})</em></div>

                @php
                    $options = "";
                    if($field->options):
                    foreach($field->options as $opt_k => $opt_v):
                        $options .= $opt_k ." : " . $opt_v . "\n";
                    endforeach;
                    endif;
                @endphp

                <textarea name="options" id="options" rows="4"
                          class="form-control">{{$options}}</textarea>
            </div>
    @endif

    <!-- *** PANEL *** -->
        <div class="form-group">
            <label for="panel">{{__('alder::lcm.panel')}}</label>
            <select name="panel" id="panel" class="form-control">

                @php
                    if(isset($field->panel))
                    $panel = $field->panel;
                @endphp

                <option value="left" @php if(isset($field->panel) && $panel === "left") echo "selected" @endphp >{{__('alder::lcm.left')}}</option>
                <option value="right" @php if(isset($field->panel) && $panel === "right") echo "selected" @endphp >{{__('alder::lcm.right')}}</option>
            </select>
        </div>

    @php $default = ""; if(isset($field->default)) $default = $field->default; @endphp
    <!-- *** DEFAULT *** -->
        <div class="form-group">
            <label for="default">{{__('alder::lcm.default')}}</label>
            <input name="default" id="default" class="form-control" value="{{$default}}">
        </div>


    @php $nullable = "fuck"; if(isset($field->nullable)) $nullable = $field->nullable; @endphp
    <!-- *** NULLABLE *** -->
        <div class="form-group">
            <label for="nullable">{{__('alder::lcm.nullable')}}</label>
            <select name="nullable" id="nullable" class="form-control">
                <option value="true" @php if($nullable && $nullable !== "fuck") echo "selected" @endphp>{{__('alder::lcm.true')}}</option>
                <option value="false" @php if(!$nullable) echo "selected" @endphp>{{__('alder::lcm.false')}}</option>
            </select>
        </div>

        <!-- *** BROWSE *** -->
        <div class="form-group d-flex align-items-center">
            <label for="browse">{{__('alder::lcm.browse')}}</label>
            <input type="checkbox" name="browse" id="browse" class="icheck"
                    @php if(in_array($field_name, $browse)) echo "checked" @endphp>

        </div>
    </div>
</div>