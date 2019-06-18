@switch($component->component)

    @case("slider")
    <div class="repeater">
        <h4 class="text-primary font-weight-bold mb-4"> Slider </h4>

        <div class="card-body">
            @foreach($component->fields as $row)
                <div class="rptr-field card shadow mb-4">
                    <div class="delete-icon rptr-field__delete">&times;</div>
                    <div class="card-header"><h5
                                class="text-primary font-weight-bold"> {{__('alder::generic.slide')}} </h5>
                    </div>
                    <div class="card-body">

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'title', 'label' => __('alder::generic.title'), 'value'=> $row->title])

                        @include("alder::components.page_builder_input",
                        ['type' => 'textarea', 'name' => 'text', 'label' => __('alder::generic.text'), 'value'=> $row->text])

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'link', 'label' => "link", 'value'=> $row->link])

                        @include("alder::components.page_builder_input",
                        ['type' => 'file', 'name' => 'image', 'label' => __('alder::generic.image'), 'value'=> $row->image])

                        <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                            <span class="text"> {{__('alder::generic.add_row')}} </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @break



    @case("tiles")
    <div class="repeater">
        <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.tiles')}} </h4>
        <div class="card-body">

            @foreach($component->fields as $row)
                <div class="rptr-field card shadow mb-4">
                    <div class="delete-icon rptr-field__delete">&times;</div>

                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold"> {{__('alder::generic.tile')}} </h5></div>
                    <div class="card-body">


                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'number', 'label' => __('alder::generic.number'), 'value'=> $row->number])

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'title', 'label' => __('alder::generic.title'), 'value'=> $row->title])

                        @include("alder::components.page_builder_input",
                        ['type' => 'file', 'name' => 'image', 'label' => __('alder::generic.image'), 'value'=> $row->image])

                        <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                            <span class="text"> {{__('alder::generic.add_row')}} </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @break

    @case("activity_chart")
    <div class="repeater">
        <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.activity_chart')}} </h4>
        <div class="card-body">
            @foreach($component->fields as $row)
                <div class="rptr-field card shadow mb-4">
                    <div class="delete-icon rptr-field__delete">&times;</div>

                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold"> {{__('alder::generic.item')}} </h5></div>
                    <div class="card-body">

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'percent', 'label' => __('alder::generic.percent'), 'value'=> $row->percent])

                        @include("alder::components.page_builder_input",
                        ['type' => 'file', 'name' => 'percent_background', 'label' => __('alder::generic.percent_bg'), 'value'=> $row->percent_background])

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'text', 'label' => __('alder::generic.text'), 'value'=> $row->text])

                        @include("alder::components.page_builder_input",
                        ['type' => 'number', 'name' => 'amount', 'label' => __('alder::generic.amount'), 'value'=> $row->amount])

                        @include("alder::components.page_builder_input",
                        ['type' => 'number', 'name' => 'amount_2', 'label' => __('alder::generic.amount') . " 2", 'value'=> $row->amount_2])

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'text_2', 'label' => __('alder::generic.text') . " 2", 'value'=> $row->text_2])

                        <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                            <span class="text"> {{__('alder::generic.add_row')}} </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @break


    @case("partners")
    <div class="repeater">
        <h4 class="text-primary font-weight-bold mb-4"> {{__('alder::generic.partners')}} </h4>

        <div class="card-body">
            @foreach($component->fields as $row)
                <div class="rptr-field card shadow mb-4">
                    <div class="delete-icon rptr-field__delete">&times;</div>

                    <div class="card-header">
                        <h5 class="text-primary font-weight-bold"> {{__('alder::generic.partner_logo')}} </h5>
                    </div>
                    <div class="card-body">

                        @include("alder::components.page_builder_input",
                        ['type' => 'text', 'name' => 'link_to_the_partner', 'label' => __('alder::generic.link_to_partner'), 'value'=> $row->link_to_the_partner])

                        @include("alder::components.page_builder_input",
                        ['type' => 'file', 'name' => 'partner_image', 'label' => __('alder::generic.partner_img'), 'value'=> $row->partner_image])

                        <div class="rptr-field__add btn btn-sm btn-primary btn-icon-split">
                                    <span class="icon text-white-50">
                                             <i class="fas fa-plus"></i>
                                    </span>
                            <span class="text"> {{__('alder::generic.add_row')}} </span>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @break


    @case("text_center_with_bg")
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{__('alder::generic.text_center_with_bg')}}</h5>
        </div>
        <div class="card-body">
            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'title', 'label' => __('alder::generic.title'), 'value'=> $component->fields->title])

            @include("alder::components.page_builder_input",
            ['type' => 'textarea', 'name' => 'text', 'label' => __('alder::generic.text'), 'value'=> $component->fields->text])

            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'link_text', 'label' => __('alder::generic.link_text'), 'value'=> $component->fields->link_text])

            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'link', 'label' => "Link", 'value'=> $component->fields->link])

            @include("alder::components.page_builder_input",
            ['type' => 'file', 'name' => 'background', 'label' => __('alder::generic.background'), 'value'=> $component->fields->background])
        </div>
    </div>
    @break

    @case("left_img_right_text")
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{__('alder::generic.left_img_right_text')}}</h5>
        </div>
        <div class="card-body">
            @include("alder::components.page_builder_input",
            ['type' => 'file', 'name' => 'image', 'label' => __('alder::generic.image'), 'value'=> $component->fields->image])

            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'title', 'label' => __('alder::generic.title'), 'value'=> $component->fields->title])

            @include("alder::components.page_builder_input",
            ['type' => 'textarea', 'name' => 'text', 'label' => __('alder::generic.text'), 'value'=> $component->fields->text])

            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'link_text', 'label' => __('alder::generic.link_text'), 'value'=> $component->fields->link_text])

            @include("alder::components.page_builder_input",
            ['type' => 'text', 'name' => 'link', 'label' => "Link", 'value'=> $component->fields->link])
        </div>
    </div>
    @break


    @case("big_img")
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5 class="text-primary font-weight-bold">{{__('alder::generic.big_img')}}</h5>
        </div>
        <div class="card-body">
            @include("alder::components.page_builder_input",
            ['type' => 'file', 'name' => 'image', 'label' => __('alder::generic.image'), 'value'=> $component->fields->image])
        </div>
    </div>
    @break

@endswitch