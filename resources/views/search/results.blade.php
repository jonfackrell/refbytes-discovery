@extends('layouts.app')

@section('content')
    <ul>

        @foreach($items as $item)

            <li>
                <a href="{{ $item['link'] }}">
                    {!! html_entity_decode($item['name']) !!}
                </a>
            </li>

        @endforeach

    </ul>
@endsection
