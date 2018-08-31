@extends('layouts.app')

@section('content')

    <div class="row">

        <div class="col-md-2">
            <div class="jumbotron jumbotron-fluid">
                <div class="container">
                    {!! BootForm::open()->get()->action(route('search')) !!}
                        <div class="row">
                            <div class="col-md-12">
                                {!! BootForm::checkbox('Available in Library Collection', 'in_collection') !!}
                                {!! BootForm::checkbox('Scholarly (Peer Reviewed) Journals', 'peer_reviewed') !!}
                                {!! BootForm::checkbox('Catalog Only', 'catalog_only') !!}
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <br/>

                                <br/>
                                <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="headingOne">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Source Types
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                @foreach($source_types as $type)
                                                    {!! BootForm::checkbox($type->Value . ' ' . $type->Count, 'source_types') !!}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                                                    Subjects
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="card-body">
                                                @foreach($subjects as $subject)
                                                    {!! BootForm::checkbox($subject->Value . ' ' . $subject->Count, 'subject') !!}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    {!! BootForm::close() !!}
                </div>
            </div>
        </div>

        <div class="col-md-8">

            @include('search.form')

            <ul class="list-unstyled">

                @foreach($items as $item)

                    <li class="media">
                        <img class="align-self-center mr-3" src="{{ $item['image'] }}" alt="Generic placeholder image">
                        <div class="media-body">
                            <h5 class="mt-0">
                                <a href="{{ $item['link'] }}">
                                    {!! html_entity_decode($item['name']) !!}
                                </a>
                            </h5>
                            <p>
                                {!! html_entity_decode($item['author']) !!}
                            </p>
                            <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</p>
                            <p class="mb-0">
                                {{--@foreach($item['subjects'] as $subject)--}}
                                    {{--<span class="badge badge-secondary">{!! $subject !!}</span>--}}
                                {{--@endforeach--}}
                            </p>
                        </div>

                    </li>

                @endforeach

            </ul>
        </div>

        <div class="col-md-2">

        </div>

    </div>

@endsection

@push('scripts')
    {{--<script src="/js/jquery-ui.min.js"></script>--}}
    {{--<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>--}}
    {{--<script src="/js/jQEditRangeSlider-min.js"></script>--}}
    {{--<script src="/js/jQAllRangeSliders-min.js"></script>--}}

    <script>



    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="/css/nouislider.min.css" />
@endpush


