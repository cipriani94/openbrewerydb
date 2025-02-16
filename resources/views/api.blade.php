@extends('layout')

@section('content')
    <!--begin::Container-->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">List of breweries</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">List of breweries</li>
                        </ol>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Breweries</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="validation-errors"></div>
                                <form id="filter-form" class="mb-4">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <input type="text" class="form-control" id="by_name" placeholder="By Name">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="by_city" placeholder="By City">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="by_country"
                                                placeholder="By Country">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <input type="text" class="form-control" id="by_state"
                                                placeholder="By State">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="by_postal"
                                                placeholder="By Postal Code">
                                        </div>
                                        <div class="col">
                                            <select class="form-control" id="by_type">
                                                <option value="">By Type</option>
                                                @foreach (App\Enums\BreweryType::all() as $type)
                                                    <option value="{{ $type }}">{{ $type }}</option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <input type="number" class="form-control" id="per_page" value="50"
                                                min="1" max="200" placeholder="Items per page">
                                        </div>
                                        <div class="col">
                                            <select class="form-control" id="sort_order">
                                                @foreach (\App\Enums\BrewerySort::all() as $orderBy)
                                                    <!-- sort=city:asc -->
                                                    <option value="{{ $orderBy . ':asc' }}">
                                                        {{ $orderBy . '  asc' }}</option>
                                                    <option value="{{ $orderBy . ':desc' }}">
                                                        {{ $orderBy . ' desc' }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Address</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Phone</th>
                                            <th>Website</th>
                                        </tr>
                                    </thead>
                                    <tbody id="breweries-table-body">

                                    </tbody>
                                </table>
                                <!-- Paginazione -->
                                <div class="mt-3 d-flex justify-content-between">
                                    <button id="prev-page" class="btn btn-secondary" disabled>← Back</button>
                                    <span id="page-info"></span>
                                    <button id="next-page" class="btn btn-secondary" disabled>Next →</button>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->

                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
    </main>
    <!--end::Container-->
@endsection
@section('js')
    <script>
        $(document).ready(function() {

            let currentPage = 1;
            const token = "{{ $_COOKIE['auth_token'] ?? null }}";
            let filters = {};

            function loadBreweries(page) {
                var params = '';
                if (page > 1) {
                    params = `?page=${page}`;
                }
                for (const key in filters) {
                    if (filters[key]) {
                        var charFilter = params == '' ? '?' : '&';
                        params += charFilter + key + '=' + filters[key];
                    }
                }
                const url = params == '' ? '/api/breweries' : `/api/breweries${params}`;
                $('#validation-errors').html("");
                $.ajax({
                    url: url,
                    method: "GET",
                    dataType: "json",
                    headers: {
                        "Authorization": `Bearer ${token}`,
                        "Accept": "application/json"
                    },
                    success: function(response) {
                        if (response.success) {
                            let rows = "";
                            response.data.forEach(brewery => {
                                rows += `
                            <tr>
                                <td>${brewery.name}</td>
                                <td>${brewery.brewery_type}</td>
                                <td>${brewery.street || 'N/A'}</td>
                                <td>${brewery.city}</td>
                                <td>${brewery.state_province || brewery.state}</td>
                                <td>${brewery.phone || 'N/A'}</td>
                                <td>${brewery.website_url ? `<a href="${brewery.website_url}" target="_blank">Sito</a>` : 'N/A'}</td>
                            </tr>
                        `;
                            });
                            $("#breweries-table-body").html(rows);

                            const {
                                total,
                                page,
                                per_page
                            } = response.meta;
                            const totalPages = Math.ceil(total / per_page);

                            $("#page-info").text(`Page ${page} of ${totalPages}`);
                            $("#prev-page").prop("disabled", parseInt(page) === 1);
                            $("#next-page").prop("disabled", parseInt(page) >= parseInt(totalPages));
                            currentPage = page;
                        } else {
                            $("#breweries-table-body").html(
                                '<tr><td colspan="7" class="text-center">Nessun birrificio trovato</td></tr>'
                            );
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Service Unavailable';
                        $("#prev-page").prop("disabled",
                            true);
                        $("#next-page").prop("disabled", true);
                        $("#page-info").text(``);
                        switch (parseInt(xhr.status)) {
                            case 404:

                                errorMessage = xhr.responseJSON.message;
                                break;
                            case 422:
                                errorMessage = 'Validation filter error';
                                let errors = xhr.responseJSON.errors;
                                let errorHtml = '';
                                for (let field in errors) {
                                    errorHtml +=
                                        `<div class="alert alert-danger">${errors[field].join(', ')}</div>`;
                                }
                                $('#validation-errors').html(errorHtml);
                                break;
                            case 401:

                                errorMessage = xhr.responseJSON.message;
                                break;

                        }
                        $("#breweries-table-body").html(
                            '<tr><td colspan="7" class="text-center text-danger">' + errorMessage +
                            '</td></tr>'
                        );
                    }
                });
            }

            loadBreweries(1);

            $("#prev-page").click(function() {
                if (parseInt(currentPage) > 1) {
                    loadBreweries(parseInt(currentPage) - 1);
                }
            });

            $("#next-page").click(function() {

                loadBreweries(parseInt(currentPage) + 1);
            });

            $("#filter-form").submit(function(e) {
                e.preventDefault();
                filters = {
                    by_name: $("#by_name").val(),
                    by_city: $("#by_city").val(),
                    by_country: $("#by_country").val(),
                    by_state: $("#by_state").val(),
                    by_postal: $("#by_postal").val(),
                    by_type: $("#by_type").val(),
                    per_page: $("#per_page").val(),
                    sort: $("#sort_order").val()
                };
                loadBreweries(1);
            });
        });
    </script>
@endsection
