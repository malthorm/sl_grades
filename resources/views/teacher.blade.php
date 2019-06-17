@extends('layout')
{{-- confirm alterts for altering deleting are you sure --}}
@section('content')
<div class="container" id="content">
    <div class="row">
        <div class="col-sm-7">
            <h3>Lehrveranstaltungen</h3>
        </div>
        <div class="col-sm-5">
            <div class="pull-right">
                <form action="{{ action('CourseController@search') }}" method="GET" role="search" id="searchForm">
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
        <!-- <a href="/courses/create">Add</a> -->
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

    <div class="alert alert-info" role="alert" hidden id="ajaxAlert">
        <strong id="ajaxAlertMsg"></strong>
    </div>

    <div class="table-responsive">
        @include('partials.courseTable')
    </div>
</div>
@endsection



@section('js')
    <script src="{{ asset('js/ajax.js') }}"></script>
@endsection


