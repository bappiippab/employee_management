@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h3>
                        <strong>Total Companies :</strong>{{ $total_companies }}
                    </h3>
                    <h3>
                        <strong>Total Employees :</strong>{{ $total_employees }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
