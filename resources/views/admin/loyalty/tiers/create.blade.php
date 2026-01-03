@extends('admin.layouts.app')

@section('title', 'إضافة مستوى')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.tiers') }}">المستويات</a></li>
                    <li class="breadcrumb-item active">إضافة مستوى</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">➕ إضافة مستوى جديد</h1>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.loyalty.tiers.store') }}" method="POST">
                    @csrf
                    @include('admin.loyalty.tiers._form')
                </form>
            </div>
        </div>
    </div>
@endsection
