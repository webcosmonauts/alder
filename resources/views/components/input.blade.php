@switch($field_modifiers->type)
    @case('string')
    <label for="{{ $field_name }}">{{ $field_name }}</label>
    <div class="input-group mb-4">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
               class="form-control"
               placeholder="{{ $field_name }}"
               aria-label="{{ $field_name }}"
               aria-describedby="{{ $field_name }}">
    </div>
    @break

    @case('relation')
    <label for="{{$field_name}}">{{ $field_name }}</label>
    <div class="input-group mb-4">
        <select name="{{$field_name}}" id="{{$field_name}}"
                class="custom-select">
            @if(isset($params->fields->$field_name->nullable) && $params->fields->$field_name->nullable)
                <option value="">—</option>
            @endif
            @foreach($relations->$field_name as $relation)
                <option value="{{$relation->id}}"
                        {{ ($edit && $relation->id == $leaf->$field_name->id) ? 'selected' : '' }}>
                    {{ $relation->title }}
                </option>
            @endforeach
        </select>
    </div>
    @break

    @case('date')
    <label for="{{ $field_name }}">{{ $field_name }}</label>
    <div class="input-group mb-4">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}"
               class="form-control datepicker"
               placeholder="{{ $field_name }}"
               aria-label="{{ $field_name }}"
               aria-describedby="{{ $field_name }}">
    </div>
    @break

    @case('image')
    <div>{{$field_name}}</div>
    <div class="input-group mb-4">
        <div class="custom-file">
            <input type="file" name="{{ $field_name }}"
                   accept="image/*"
                   class="custom-file-input" id="{{ $field_name }}"
                   aria-describedby="{{ $field_name }}">
            <label class="custom-file-label" for="{{$field_name}}">Choose
                file</label>
        </div>
    </div>
    @break

    @case('dropdown')
    <label for="{{$field_name}}"> {{$field_name}} </label>
    <div class="input-group mb-4">
        <select name="{{$field_name}}" id="{{$field_name}}"
                class="custom-select">
            @foreach($field_modifiers->options as $option => $opt)
                <option value="{{$option}}">{{$opt}}</option>
            @endforeach
        </select>
    </div>
    @break

    @case('template')
    <label for="{{$field_name}}"> {{$field_name}} </label>
    <div class="input-group mb-4">
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
