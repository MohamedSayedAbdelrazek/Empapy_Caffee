@extends('admin.layouts.app')

@section('title', 'إضافة مكافأة')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.dashboard') }}">نظام الولاء</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.loyalty.rewards') }}">المكافآت</a></li>
                    <li class="breadcrumb-item active">إضافة مكافأة</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 mt-2">➕ إضافة مكافأة جديدة</h1>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form action="{{ route('admin.loyalty.rewards.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.loyalty.rewards._form')
                </form>
            </div>
        </div>
    </div>
@endsection
