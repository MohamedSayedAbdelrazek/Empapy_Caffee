@extends('admin.layouts.app')

@section('title', 'تعديل مستوى')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.tiers') }}">المستويات</a></li>
                    <li class="breadcrumb-item active">تعديل {{ $tier->name_ar }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">✏️ تعديل {{ $tier->name_ar }}</h1>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.loyalty.tiers.update', $tier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('admin.loyalty.tiers._form', ['tier' => $tier])
                </form>
            </div>
        </div>
    </div>
@endsection
