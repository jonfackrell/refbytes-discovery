<div class="jumbotron jumbotron-fluid">
    <div class="container">
        {!! BootForm::open()->get()->action(route('search')) !!}
            <div class="row">
                <div class="col-md-10">
                    {!! BootForm::text('Search', 'search_term_1')->hideLabel() !!}
                </div>
                <div class="col-md-2">
                    {!! BootForm::submit('Search')->addClass('btn-block') !!}
                </div>
            </div>
        {!! BootForm::close() !!}
    </div>
</div>

