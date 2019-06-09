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
        <select name="{{$field_name}}" id="{{$field_name}}"
                class="custom-select">
                @foreach($templates_object as $name=>$single_template)
                    <option value="{{$single_template['template_name']}}">{{$single_template['label']}}</option>
                @endforeach
        </select>
    </div>
    @break

@endswitch
