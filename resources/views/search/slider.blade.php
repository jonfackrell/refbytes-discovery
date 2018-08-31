@extends('layouts.test')

@section('content')

    <div id="date-range-slider"></div>

@endsection

@push('scripts')
    {{--<script src="/js/jquery-ui.min.js"></script>--}}
    {{--<script src="http://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>--}}
    {{--<script src="/js/jQEditRangeSlider-min.js"></script>--}}
    {{--<script src="/js/jQAllRangeSliders-min.js"></script>--}}
    <script src="/js/nouislider.min.js"></script>
    <script>

        $(function(){
            var behaviourSlider = document.getElementById('date-range-slider');

            noUiSlider.create(behaviourSlider, {
                start: [ 20, 40 ],
                animate: true,
                tooltips: [true, true],
                step: 1,
                behaviour: 'tap-drag',
                connect: true,
                range: {
                    'min':  [0],
                    'max':  [100]
                }
            });
        });

    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="/css/nouislider.min.css" />
@endpush


