@switch($type)

    @case('password')
    @case('time')
    @case('color')
    @case('month')
    @case('number')
    @case('text')
    <label for="{{ $field_name }}">{{ $label }}</label>
    <div class="input-group mb-2">
        <input type="{{$type}}" name="{{ $field_name }}" id="{{ $field_name }}"
               class="form-control"
               placeholder="{{ $field_name }}"
               aria-label="{{ $field_name }}"
               aria-describedby="{{ $field_name }}">
    </div>
    @break


    @case('repeater')
    <div class="repeater card shadow mb-5">
        <div class="card-header"><h5 class="text-primary font-weight-bold">{{$label}}</h5></div>

        <div class="card-body">
            <div class="rptr-field card shadow">

                <div class="rptr-field__delete delete-icon">&times;</div>


                <div class="card-header text-primary font-weight-bold">
                    {{__('alder::generic.fields_row')}}
                </div>

                <div class="card-body">

                    @foreach($k->fields as $lcm_subitem1 => $k1)
                        @php
                            $field_name = $lcm_subitem1;
                            $label = $k1->display_name;
                            $field = $k1;
                        @endphp

                        @if(isset($k1->type)) @php $type = $k1->type; @endphp @endif

                        @include('alder::components.input')
                    @endforeach


                    <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                             <i class="fas fa-plus"></i>
                    </span>
                        <span class="text"> {{__('alder::generic.add_row')}} </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @break

    @case('relation')
    <label for="{{ $field_name }}">{{ $label }}</label>
    <div class="input-group mb-2">
        @if($k->relation_type == 'belongsTo')
            <select class="custom-select" name="{{ $field_name }}" id="{{ $field_name }}">
                @if(isset($k->nullable) && $k->nullable)
                    <option value="">—</option>
                @endif
                @foreach($relations->$field_name as $relation)
                    <option value="{{ $relation->id }}"
                            {{ ($edit && $relation->id == ($leaf->$field_name->id ?? null)) ? 'selected' : '' }}>
                        {{ $relation->title }}
                    </option>
                @endforeach
            </select>
        @elseif($k->relation_type == 'belongsToMany')
            @php
                $ids = ($edit && !empty($leaf->$field_name)) ? $leaf->$field_name->pluck('id')->toArray() : null;
            @endphp
            <select multiple class="custom-select" name="{{ $field_name }}[]" id="{{ $field_name }}">
                @if(isset($k->nullable) && $k->nullable)
                    <option value="">—</option>
                @endif
                @foreach($relations->$field_name as $relation)
                    <option value="{{ $relation->id }}" {{ $edit && in_array($relation->id, $ids) ? 'selected' : '' }}>{{ $relation->title }}</option>
                @endforeach
            </select>
        @endif
    </div>
    @break

    @case('date')
    <label for="{{ $field_name }}">{{ $label }}</label>
    <div class="input-group mb-2">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
               class="form-control datepicker"
               placeholder="{{ $field_name }}"
               aria-label="{{ $field_name }}"
               aria-describedby="{{ $field_name }}">
    </div>
    @break


    @case('datetime-local')
    <label for="{{ $field_name }}">{{ $label }}</label>
    <div class="input-group mb-2">
        <input type="datetime-local" name="{{ $field_name }}" id="{{ $field_name }}"
               class="form-control datetimepicker"
               placeholder="{{ $field_name }}"
               aria-label="{{ $field_name }}"
               aria-describedby="{{ $field_name }}">
    </div>
    @break



    @case('radio')
    @case('checkbox')
    @if(isset($field->options))
        <label> {{$label}}</label>
        <div class=" mb-2">
            @foreach($field->options as $opt_val => $opt_label)

                <div>
                    <label for="{{$opt_val}}" class="mr-2"> {{$opt_label}} <input type="{{$type}}" name="{{$field_name}}"
                                                                     id="{{$opt_val}}"
                                                                     value="{{$opt_val}}"
                                                                     class="icheck"></label>
                </div>
            @endforeach
        </div>
    @endif
    @break;



    @case('file-multiple')
    @case('file')
    <div>{{$field_name}}</div>
    <div class="input-group mb-2">
        <div class="custom-file">
            <input type="file" name="{{ $field_name }}"
                   @php if($type === "file-multiple") echo 'multiple'; @endphp
                   class="custom-file-input" id="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
            <label class="custom-file-label" for="{{$field_name}}">{{__('alder::generic.choose_file')}}</label>
        </div>
    </div>
    @break


    @case('select')
    @case('select-multiple')
    <label for="{{$field_name}}"> {{$label}} </label>
    <div class="input-group mb-2">
        <select name="{{$field_name}}" id="{{$field_name}}"
                @php if($type === "select-multiple") echo "multiple";@endphp
                class="custom-select">
            @foreach($field->options as $option => $opt)
                <option value="{{$option}}">{{$opt}}</option>
            @endforeach
        </select>
    </div>
    @break


    @case('template')
    <label for="{{$field_name}}"> {{$label}} </label>
    <div class="input-group mb-2">
        @php
            $templates_object = TemplateHelper::getTemplatesObject("alder");
        @endphp
        @php
            $selected_template = $edit ? $leaf->LCMV->values->template : "";
        @endphp
        <select name="{{$field_name}}" id="{{$field_name}}" class="custom-select">
            <option {{ empty($selected_template) ? "selected" : "" }} value="">{{ __("alder::theme.no_template_specified") }}</option>
            @foreach($templates_object as $name=>$single_template)
                <option
                        {{$selected_template == $single_template['template_name'] ? "selected" : ""}} value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
            @endforeach
        </select>
    </div>
    @break

@endswitch
