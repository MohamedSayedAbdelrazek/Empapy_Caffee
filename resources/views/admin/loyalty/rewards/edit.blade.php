@extends('admin.layouts.app')

@section('title', 'تعديل مكافأة')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.rewards') }}">المكافآت</a></li>
                    <li class="breadcrumb-item active">تعديل {{ $reward->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">✏️ تعديل {{ $reward->name }}</h1>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.loyalty.rewards.update', $reward) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.loyalty.rewards._form', ['reward' => $reward])
                </form>
            </div>
        </div>
    </div>
@endsection
