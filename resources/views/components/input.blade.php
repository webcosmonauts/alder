@switch($type)

    @case('password')
    @case('color')
    @case('month')
    @case('number')
    @case('text')

    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{ $field_name }}">{{ $label }}</label>
        <div class="input-group mb-2">
            <input type="{{$type}}" name="{{ $field_name }}" id="{{ $field_name }}"
                   class="form-control"
                   value="{{$field_value}}"
                   placeholder="{{ $label }}"
                   aria-label="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
        </div>
    </div>
    @break


    @case('repeater')
    <div data-condition="{{$conditions_str}}" hidden>
        <div class="repeater card shadow mb-5" data-name="{{ $field_name }}">
            <div class="card-header"><h5 class="text-primary font-weight-bold">{{$label}}</h5></div>

            <div class="card-body">

                @if($edit && $field_value)

                    @php $parent_field = $field; @endphp

                    @foreach($field_value as $values)

                        <div class="rptr-field card shadow">
                            <div class="rptr-field__delete delete-icon">&times;</div>

                            <!-- actions -->
                            <div class="rptr-field__actions">
                                <div class="circle-icon" title="W górę" data-action="up">
                                    <em class="fa fa-angle-up"></em>
                                </div>
                                <div class="circle-icon" title="Na dół" data-action="down">
                                    <em class="fa fa-angle-down"></em></div>
                            </div>

                            <div class="card-header text-primary font-weight-bold">
                                {{__('alder::generic.fields_row')}}
                            </div>

                            <div class="card-body">

                                @if(isset($parent_field->fields))
                                    @foreach($parent_field->fields as $lcm_subitem1 => $k1)

                                        @php
                                            $field_name = $lcm_subitem1;
                                            $label = $k1->display_name;
                                            $field = $k1;

                                            if(isset($values->$field_name))
                                                $field_value = $values->$field_name;

                                            if($k1->type === 'checkbox' || $k1->type === 'radio'){
                                                $field_name = Alder::chooseNameFormRptr($field_name, $values);
                                                $field_value = $values->$field_name;
                                            }

                                        @endphp

                                        @if(isset($k1->type))
                                            @php $type = $k1->type; @endphp
                                        @endif

                                        @include('alder::components.input')
                                    @endforeach
                                @endif


                                <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                <span class="icon text-white-50">
                                         <i class="fas fa-plus"></i>
                                </span>
                                    <span class="text"> {{__('alder::generic.add_row')}} </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="rptr-field card shadow">
                        <div class="rptr-field__delete delete-icon">&times;</div>

                        <!-- actions -->
                        <div class="rptr-field__actions">
                            <div class="circle-icon" title="W górę" data-action="up">
                                <em class="fa fa-angle-up"></em>
                            </div>
                            <div class="circle-icon" title="Na dół" data-action="down">
                                <em class="fa fa-angle-down"></em></div>
                        </div>

                        <div class="card-header text-primary font-weight-bold">
                            {{__('alder::generic.fields_row')}}
                        </div>

                        <div class="card-body">
                            @foreach($field->fields as $lcm_subitem1 => $k1)
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
                @endif
            </div>
        </div>
    </div>
    @break

    @case('relation')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{ $field_name }}">{{ $label }}</label>
        <div class="mb-2">
            @if($k->relation_type == 'belongsTo')
                <select class="custom-select" name="{{ $field_name }}" id="{{ $field_name }}">
                    @if(isset($k->nullable) && $k->nullable)
                        <option value="">—</option>
                    @endif
                    @foreach($relations->$field_name as $relation)
                        <option value="{{ $relation->id }}"
                        @if(isset($tab) && $tab)
                            {{ ($edit && $relation->id == ($leaf[$tab]->$field_name->id ?? null)) ? 'selected' : '' }}>
                            @else
                                {{ ($edit && $relation->id == ($leaf->$field_name->id ?? null)) ? 'selected' : '' }}>
                            @endif

                            {{ $relation->title }}
                        </option>
                    @endforeach
                </select>
            @elseif($k->relation_type == 'belongsToMany')
                @php
                    $ids = ($edit && !empty($leaf->$field_name)) ? $leaf->$field_name->pluck('id')->toArray() : [];
                @endphp
                <select multiple class="custom-select" name="{{ $field_name }}" id="{{ $field_name }}">
                    @if(isset($k->nullable) && $k->nullable)
                        <option value="">—</option>
                    @endif
                    @foreach($relations->$field_name as $relation)
                        <option value="{{ $relation->id }}" {{ $edit && in_array($relation->id, $ids) ? 'selected' : '' }}>{{ $relation->title }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
    @break

    @case('date')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{ $field_name }}">{{ $label }}</label>
        <div class="input-group mb-2">
            <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
                   value="{{$field_value}}"
                   data-toggle="datetimepicker"
                   data-target="#{{$field_name}}"
                   class="form-control datepicker"
                   placeholder="{{ $label }}"
                   aria-label="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
        </div>
    </div>
    @break

    @case('time')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{ $field_name }}">{{ $label }}</label>
        <div class="input-group mb-2">
            <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
                   value="{{$field_value}}"
                   data-toggle="datetimepicker"
                   data-target="#{{$field_name}}"
                   class="form-control timepicker"
                   placeholder="{{ $label }}"
                   aria-label="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
        </div>
    </div>
    @break

    @case('datetime-local')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{ $field_name }}">{{ $label }}</label>
        <div class="input-group mb-2">
            <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
                   data-toggle="datetimepicker"
                   data-target="#{{$field_name}}"
                   value="{{$field_value}}"
                   class="form-control datetimepicker"
                   placeholder="{{ $label }}"
                   aria-label="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
        </div>
    </div>
    @break




    @case('checkbox')
    @if(is_array($field->options))
        <div data-condition="{{$conditions_str}}" hidden>
            <label> {{$label}}</label>
            <div class=" mb-2">
                @foreach($field->options as $opt_val => $opt_label)

                    <div>
                        <label for="" class="mr-2"> {{$opt_label}}
                            <input type="{{$type}}" name="{{$field_name}}"
                                   id="{{$opt_val}}"
                                   value="{{$opt_val}}"
                                   @if($edit && isset($field_value))
                                   @foreach($field_value as $val)
                                   @if($val === $opt_val) checked @endif
                                   @endforeach
                                   @endif
                                   class="icheck"></label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @break


    @case('radio')
    @if(isset($field->options))
        <div data-condition="{{$conditions_str}}" hidden>
            <label> {{$label}}</label>
            <div class=" mb-2">
                @foreach($field->options as $opt_val => $opt_label)

                    <div>
                        <label for="" class="mr-2"> {{$opt_label}}
                            <input type="{{$type}}" name="{{$field_name}}"
                                   id="{{$opt_val}}"
                                   value="{{$opt_val}}"
                                   @if(isset($field_value) && $edit && $field_value === $opt_val) checked @endif
                                   class="icheck"></label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @break


    @case('file-multiple')
    @case('file')
    <div data-condition="{{$conditions_str}}" hidden>
        <div>{{$label}}</div>
        <div class="input-group">
            <input type="text" class="image_label form-control" name="{{$field_name}}"
                   aria-label="Image"
                   aria-describedby="button-image" @if($edit) value="{{$field_value}}" @endif>
            <div class="input-group-append">
                <button class="btn btn-sm mb-0 mt-0 btn-outline-secondary button-image" style="height: 35px;"
                        type="button">{{ __('alder::generic.choose') }}</button>
            </div>
        </div>
    </div>
    @break


    @case('select')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{$field_name}}"> {{$label}} </label>
        <div class="mb-2">
            <select name="{{$field_name}}" id="{{$field_name}}"
                    class="custom-select">
                @foreach($field->options as $option => $opt)
                    <option value="{{$option}}"
                            @if($edit)

                            @if($field_value === $option) selected @endif

                            @endif
                    >{{$opt}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @break


    @case('select-multiple')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{$field_name}}"> {{$label}} </label>
        <div class="mb-2">
            <select name="{{$field_name}}" id="{{$field_name}}" class="form-control" multiple>
                @foreach($field->options as $option => $opt)
                    <option value="{{$option}}"
                            @if($edit && is_array($field_value))
                            @foreach($field_value as $val)
                            @if($val === $option) selected @endif
                            @endforeach
                            @endif

                    >{{$opt}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @break


    @case('template')
    <div data-condition="{{$conditions_str}}" hidden>
        <label for="{{$field_name}}"> {{$label}} </label>
        <div class="mb-2">
            @php
                $templates_object = TemplateHelper::getTemplatesObject("alder");
                $selected_template = $edit ? $leaf->LCMV->values->template ?? '' : "";
            @endphp
            <select name="{{$field_name}}" id="{{$field_name}}" class="custom-select">
                <option {{ empty($selected_template) ? "selected" : "" }} value="">{{ __("alder::theme.no_template_specified") }}</option>
                @foreach($templates_object as $name=>$single_template)
                    <option
                            {{$selected_template == $single_template['template_name'] ? "selected" : ""}} value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @break
@endswitch
