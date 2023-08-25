@extends('crudHelper::themes.layouts.base')

@section('content')

    {!! $viewModel->component->render() !!}

@endsection
