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

                    <p>
                    You are logged in!
                    </p>

                    <p>
                        <a href="{{ route('webauthn.register') }}">
                            Register a new security key.
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
