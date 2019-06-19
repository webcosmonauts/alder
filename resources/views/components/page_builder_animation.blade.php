@if( !$editing || $component->component !== "slider")
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{__('alder::generic.animation')}}</h5>
        </div>
        <div class="card-body">

            <div class="mb-2">
                <label> {{__('alder::generic.animation_type')}} </label>

                <select name="section_animation" class="custom-select">

                    @php

                        $animation_types = [

                           "bounce",
                           "flash",
                           "pulse",
                           "rubberBand",
                           "shake",
                           "swing",
                           "tada",
                           "wobble",
                           "jello",
                           "heartBeat",

                           "bounceIn",
                           "bounceInDown",
                           "bounceInLeft",
                           "bounceInRight",
                           "bounceInUp",

                           "bounceOut",
                           "bounceOutDown",
                           "bounceOutLeft",
                           "bounceOutRight",
                           "bounceOutUp",

                           "fadeInDown",
                           "fadeInDownBig",
                           "fadeInLeft",
                           "fadeInLeftBig",
                           "fadeInRight",
                           "fadeInRightBig",
                           "fadeInUp",
                           "fadeInUpBig",

                           "fadeOut",
                           "fadeOutDown",
                           "fadeOutDownBig",
                           "fadeOutLeft",
                           "fadeOutLeftBig",
                           "fadeOutRight",
                           "fadeOutRightBig",
                           "fadeOutUp",
                           "fadeOutUpBig",

                           "flip",
                           "flipInX",
                           "flipInY",
                           "flipOutX",
                           "flipOutY",

                           "lightSpeedIn",
                           "lightSpeedOut",

                           "rotateIn",
                           "rotateInDownLeft",
                           "rotateInDownRight",
                           "rotateInUpLeft",
                           "rotateInUpRight",

                           "rotateOut",
                           "rotateOutDownLeft",
                           "rotateOutDownRight",
                           "rotateOutUpLeft",
                           "rotateOutUpRight",

                           "slideInUp",
                           "slideInDown",
                           "slideInLeft",
                           "slideInRight",


                           "slideOutUp",
                           "slideOutDown",
                           "slideOutLeft",
                           "slideOutRight",

                           "zoomIn",
                           "zoomInDown",
                           "zoomInLeft",
                           "zoomInRight",
                           "zoomInUp",

                           "zoomOut",
                           "zoomOutDown",
                           "zoomOutLeft",
                           "zoomOutRight",
                           "zoomOutUp",

                           "hinge",
                           "jackInTheBox",
                           "rollIn",
                           "rollOut",

                        ];
                    @endphp

                    <option disabled selected> ---</option>
                    @foreach($animation_types as $animation_type)
                        <option value="{{$animation_type}}"
                                @if($editing && isset($component->fields->section_animation) && $component->fields->section_animation == $animation_type) selected @endif>{{$animation_type}}</option>
                    @endforeach

                </select>
            </div>


            <div class="mb-2">
                <label>{{__('alder::generic.animation_duration')}}</label>
                <input type="number" name="section_animation_duration" class="form-control"
                       @if($editing && isset($component->fields->section_animation_duration)) value="{{$component->fields->section_animation_duration}}" @endif>
            </div>
        </div>
    </div>
@endif