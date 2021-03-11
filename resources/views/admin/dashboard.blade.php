@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

    <h4 class="font-weight-bold py-3 mb-4">
        Dashboard
        <div class="text-muted text-tiny mt-1">
            <small class="font-weight-normal">
                Today is&nbsp;{{ date('l ,d F Y') }}
            </small>
        </div>
    </h4>

    <div class="row">
        <div class="col-sm-6 col-xl-3">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-pencil display-4 text-success"></div>
                        <div class="ml-3">
                            <div class="text-muted small">Articles</div>
                            <div class="text-large">{{ \App\Models\Post::articles()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-xl-3">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-file-empty display-4 text-info"></div>
                        <div class="ml-3">
                            <div class="text-muted small">Pages</div>
                            <div class="text-large">{{ \App\Models\Post::pages()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-xl-3">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-question-circle display-4 text-danger"></div>
                        <div class="ml-3">
                            <div class="text-muted small">FAQs</div>
                            <div class="text-large">{{ \App\Models\Post::faq()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-xl-3">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="lnr lnr-users display-4 text-warning"></div>
                        <div class="ml-3">
                            <div class="text-muted small">Users</div>
                            <div
                                class="text-large">{{ \App\Models\User::notSuper()->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
