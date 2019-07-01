@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-7">

            <h3>Lehrveranstaltungen</h3>
            <noscript>
                <a href="courses/create">Neue Lehrveranstaltung</a>
            </noscript>
        </div>
        <div class="col-sm-5">
            <div class="pull-right">
                <form action="{{ action('CourseController@search') }}" method="GET" role="search" id="searchForm">
                    @csrf
                    <div class="input-group">
                        <input type="text"
                            class="form-control"
                            placeholder="Suche"
                            aria-label="Suche"
                            id="searchInput"
                            name="query"
                            role="search">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        @include('modals')
    </div>

    @if (session('success'))
    <div class="alert alert-success" role="alert">
        <strong>{{ session('success') }}</strong>
    </div>
    @elseif (session('danger'))
    <div class="alert alert-danger" role="alert">
        <strong>{{ session('danger') }}</strong>
    </div>
    @elseif (session('delete'))
    <div class="alert alert-success" role="alert">
        <strong>{{ session('delete') }}</strong>
    </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-info" role="alert" hidden id="ajaxAlert">
        <strong id="ajaxAlertMsg"></strong>
    </div>

    <div class="table-responsive">
        @include('partials.courseTable')
    </div>
@endsection



@section('js')
    <script src="{{ asset('js/ajax.js') }}"></script>
@endsection


