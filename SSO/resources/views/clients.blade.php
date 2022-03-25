@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   @foreach ($clients as $client)
                        <div class="py-3 text-gray-900">
                        <p class=" text-gray-500"><b>Name</b>: {{ $client->name }}</p>
                        <p><b>Client Id</b> : {{ $client->id }}</p>
                        <p > <b>Client Redirect</b> : {{ $client->redirect }}</p>
                        <p > <b>Client Secret</b> : {{ $client->secret }}</p>
                        </div>
                   @endforeach
                </div>
            </div>
        </div>
        <div class="col-md-8 mt-4">
        <div class="card">
            <div class="card-header"><strong>{{ __('Novo Cliente') }}</strong></div>
            <div class="card-body">
                <form action="/oauth/clients" method="post">
                    @csrf
                  <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name"  class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="redirect">Url</label>
                    <input type="text" name="redirect" id="redirect"  class="form-control">
                  </div>
                  <div class="mt-2">
                      <button type="submit" class="btn btn-dark w-100">Criar Cliente</button>
                  </div>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection
