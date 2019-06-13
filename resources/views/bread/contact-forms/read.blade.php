@extends('alder::layouts.main')

@section('scripts-body')

    <link rel="stylesheet" href="{{asset('/vendor/contact-form/css/contact-form.css')}}">
    <script src="{{asset('/vendor/contact-form/contact-form.js')}}"></script>
@endsection

@section('content')


    <a href="{{route('alder.contact-forms.index')}}" class="btn btn-success mb-3"> {{__('alder::generic.back')}}</a>

    <div class="card shadow">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{$forms->title}}</h5>
        </div>

        <div class="card-body">

            @foreach ($lin as $key => $value)
                @switch($value)
                    @case('text')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="text" name="{{$lin[$key + 2]}}"><br>
                    @break
                    @case('text*')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="text" name="{{$lin[$key + 2]}}" required><br>
                    @break
                    @case('email')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="email" size="30" name="{{$lin[$key + 2]}}"><br>
                    @break
                    @case('email*')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="email" size="30" name="{{$lin[$key + 2]}}" required><br>
                    @break
                    @case('tel')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="tel" name="{{$lin[$key + 2]}}"><br>
                    @break
                    @case('tel*')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>
                    <input class="form-control" type="tel" name="{{$lin[$key + 2]}}" required><br>
                    @break
                    @case('date')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>

                    <input class="form-control" type="date" name="{{$lin[$key + 2]}}"
                           value=""
                           min="1900-01-01" max="2118-12-31"><br>
                    @break
                    @case('date*')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label>

                    <input class="form-control" type="date" name="{{$lin[$key + 2]}}"
                           value=""
                           min="1900-01-01" max="2118-12-31" required><br>
                    @break
                    @case('textarea')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label><br>

                    <textarea class="form-control" name="{{$lin[$key + 2]}}"
                              rows="5" cols="33"></textarea><br>
                    @break
                    @case('textarea*')
                    <label for="{{$lin[$key + 2]}}">{{$lin[$key + 2]}}:</label><br>

                    <textarea class="form-control" name="{{$lin[$key + 2]}}"
                              rows="5" cols="33" required></textarea><br>
                    @break
                    @case('email')
                    <label for="email">{{$value}}</label>
                    <input type="email" id="email" size="30" name="{{$lin[$key + 2]}}">
                    @break
                    @case('email*')
                    <label for="email">{{$value}}</label>
                    <input type="email" id="email" size="30" name="{{$lin[$key + 2]}}" required>
                    @break
                    @case('file')
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="{{$lin[$key + 2]}}">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="{{$lin[$key + 2]}}"
                                   aria-describedby="inputGroupFileAddon01">
                            <label class="custom-file-label" for="{{$lin[$key + 2]}}">Choose file</label>
                        </div>
                    </div>
                    <br>
                    @break
                    @case('file*')
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="{{$lin[$key + 2]}}">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="{{$lin[$key + 2]}}"
                                   aria-describedby="inputGroupFileAddon01" required>
                            <label class="custom-file-label" for="{{$lin[$key + 2]}}">Choose file</label>
                        </div>
                    </div>
                    <br>
                    @break





                    @case('select')

                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";
                    if (strpos($required, '*')) {
                        $required = "required";
                    }

                    switch ($single_line_array[0]){
                    case("select"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));?>

                    <select class='form-control'>
                        @foreach($splitted as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <?php
                    break;
                    }
                    endforeach;

                    ?>
                    <br>
                    @break


                    @case('select*')
                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";
                    if (strpos($required, '*')) {
                        $required = "required";
                    }

                    switch ($single_line_array[0]){
                    case("select*"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));?>

                    <select class='form-control'>
                        @foreach($splitted as $val)
                            <option value="{{$val}}">{{$val}}</option>
                        @endforeach
                    </select>
                    <?php
                    break;
                    }
                    endforeach;
                    ?>
                    <br>
                    @break

                    @case('checkbox*')
                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";


                    if (strpos($required, '*')) {
                        $required = "required";
                    }
                    switch ($single_line_array[0]){
                    case("checkbox*"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));
                    $radiob = explode(':', $single_line_array[1]);
                    ?>
                    <fieldset required>
                        @foreach($splitted as $val)

                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="{{$radiob[1]}}" id="{{$val}}"
                                       value="{{$val}}">
                                <label class="custom-control-label" for="{{$val}}">{{$val}}</label>
                            </div>
                        @endforeach
                    </fieldset>
                    <?php
                    break;
                    }
                    endforeach;
                    ?>
                    <br>
                    @break

                    @case('checkbox')
                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";
                    if (strpos($required, '*')) {
                        $required = "required";
                    }
                    switch ($single_line_array[0]){
                    case("checkbox"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));
                    $radiob = explode(':', $single_line_array[1]);

                    ?>
                    <fieldset>
                        @foreach($splitted as $val)
                            <div>
                                <input class="custom-control-input" type="checkbox" name="{{$radiob[1]}}" id="{{$val}}"
                                       value="{{$val}}">
                                <label class="custom-control-label" for="{{$val}}">{{$val}}</label>
                            </div>
                        @endforeach
                    </fieldset>
                    <?php
                    break;
                    }
                    endforeach;
                    ?>
                    <br>
                    @break


                    @case('radio*')
                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";
                    if (strpos($required, '*')) {
                        $required = "required";
                    }
                    switch ($single_line_array[0]){
                    case("radio*"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));
                    $radiob = explode(':', $single_line_array[1]);

                    ?>
                    @foreach($splitted as $val)
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="{{$val}}" name="{{$radiob[1]}}"
                                   value="{{$val}}" required>
                            <label class="custom-control-label" for="{{$val}}">{{$val}}</label>
                        </div>
                    @endforeach
                    <?php
                    break;
                    }
                    endforeach;
                    ?>
                    <br>
                    @break
                    @case('radio')
                    <?php
                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);
                    $required = "";
                    if (strpos($required, '*')) {
                        $required = "required";
                    }
                    switch ($single_line_array[0]){
                    case("radio*"):
                    $splitted = explode(',', rtrim(explode('options:"', $sel)[1], '"'));
                    $radiob = explode(':', $single_line_array[1]);
                    ?>
                    <fieldset>
                        @foreach($splitted as $val)
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" type="radio" id="{{$val}}" name="{{$radiob[1]}}"
                                       value="{{$val}}">
                                <label class="custom-control-label" for="{{$val}}">{{$val}}</label>
                            </div>
                        @endforeach
                    </fieldset>
                    <?php
                    break;
                    }
                    endforeach;
                    ?>
                    <br>
                    @break


                    @case('submit')
                    <input class="btn btn-success" type="submit" value="{{$lin[$key + 1]}}"><br><br>
                    @break

                    @case('acceptance')
                    <?php

                    foreach ($lincs as $sel):
                    $single_line_array = explode(' ', $sel);


                    switch ($single_line_array[0]){
                    case("acceptance"):

                    $name_accept = explode(':', $single_line_array[1]);
                    $single_line = rtrim(explode(':"', $sel)[1], '"');
                    ?>
                    <div class="custom-control custom-radio">
                        <input class="custom-control-input" type="checkbox" id="{{$name_accept[1]}}"
                               name="{{$name_accept[1]}}" required>
                        <label class="custom-control-label" for="{{$name_accept[1]}}">{{$single_line}}</label>
                    </div>

                    <?php
                    break;
                    }
                    endforeach
                    ?>
                    @break

                    @default



                @endswitch
            @endforeach
            <br>

        </div>
    </div>











@endsection