<div class="container-fluid">
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab aperiam commodi eveniet inventore mollitia natus
    pariatur repudiandae voluptatem! Ab cum deleniti deserunt harum id molestias nobis obcaecati repellendus temporibus
    voluptates.

    <style>
        textarea,
        select,
        input:not([type=checkbox]):not([type=radio]) {
            display: block;
            margin-bottom: 10px;
        }
    </style>

    <form action="" method="POST" class="form" hidden>
        [text* name:your-name]
        [email* name:your-email]
        [tel* name:your-tel]
        [date* name:dateofregistration]


        [select* name:country options:"Russia,Ukraine,Poland"]

        <p>
            <span style="display: block">Are you ready</span>
            [checkbox* name:areyouready options:"yes,no"]
        </p>

        <p>
            <span style="display: block">Are you pidor</span>
            [radio* name:areyoupidor options:"yes,no,maybe"]
        </p>

        [acceptance name:argeement condition:"You must accept this term!"]
        [file* name:CSV filetypes:.doc|.pdf|.txt|.jpg]
        [submit "Submit"]
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="{{ asset('vendor/contact-form/js/contact-form-parser.js') }}"></script>