@extends('admin.layouts.app')

@section('title', 'لوحة الطلبات - Kanban Board')

@push('styles')
    <style>
        /* ==========================================
                                                               🎨 PREMIUM KANBAN BOARD STYLES
                                                               WOW-Factor Design with Glassmorphism
                                                               ========================================== */

        /* Main Container */
        .kanban-container {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            overflow-x: auto;
            min-height: calc(100vh - 200px);
            scrollbar-width: thin;
            scrollbar-color: var(--admin-primary) transparent;
        }

        .kanban-container::-webkit-scrollbar {
            height: 8px;
        }

        .kanban-container::-webkit-scrollbar-thumb {
            background: var(--admin-primary);
            border-radius: 10px;
        }

        /* Kanban Column */
        .kanban-column {
            flex: 0 0 320px;
            min-width: 320px;
            background: rgba(26, 26, 46, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 220px);
            transition: all 0.3s ease;
        }

        .kanban-column:hover {
            border-color: rgba(201, 162, 39, 0.3);
            box-shadow: 0 0 40px rgba(201, 162, 39, 0.1);
        }

        .kanban-column.drag-over {
            background: rgba(201, 162, 39, 0.1);
            border-color: var(--admin-primary);
            transform: scale(1.02);
            box-shadow: 0 0 60px rgba(201, 162, 39, 0.3);
        }

        /* Column Header */
        .kanban-column-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .kanban-column-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            border-radius: 20px 20px 0 0;
        }

        .kanban-column.status-pending .kanban-column-header::before {
            background: linear-gradient(90deg, #f59e0b, #d97706);
        }

        .kanban-column.status-processing .kanban-column-header::before {
            background: linear-gradient(90deg, #3b82f6, #2563eb);
        }

        .kanban-column.status-shipped .kanban-column-header::before {
            background: linear-gradient(90deg, #8b5cf6, #7c3aed);
        }

        .kanban-column.status-delivered .kanban-column-header::before {
            background: linear-gradient(90deg, #10b981, #059669);
        }

        .kanban-column.status-cancelled .kanban-column-header::before {
            background: linear-gradient(90deg, #ef4444, #dc2626);
        }

        .column-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }

        .column-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 1.2rem;
        }

        .status-pending .column-icon {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .status-processing .column-icon {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .status-shipped .column-icon {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }

        .status-delivered .column-icon {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .status-cancelled .column-icon {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .column-count {
            margin-right: auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Column Body (Scrollable) */
        .kanban-column-body {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: 200px;
        }

        .kanban-column-body::-webkit-scrollbar {
            width: 6px;
        }

        .kanban-column-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        /* Empty Column State */
        .kanban-empty {
            text-align: center;
            padding: 40px 20px;
            color: var(--admin-text-muted);
            opacity: 0.6;
        }

        .kanban-empty i {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        /* Order Card */
        .order-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 16px;
            cursor: grab;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px) scale(1.02);
            border-color: rgba(201, 162, 39, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(201, 162, 39, 0.2);
        }

        .order-card:hover::before {
            opacity: 1;
        }

        .order-card.dragging {
            opacity: 0.8;
            transform: rotate(3deg) scale(1.05);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
            cursor: grabbing;
            z-index: 1000;
        }

        .order-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .order-number {
            font-weight: 700;
            font-size: 1rem;
            color: var(--admin-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-number i {
            font-size: 0.9rem;
        }

        .order-time {
            font-size: 0.75rem;
            color: var(--admin-text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .order-customer {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .customer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--admin-primary), #e8c547);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            color: #1a1a2e;
        }

        .customer-info h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
        }

        .customer-info small {
            color: var(--admin-text-muted);
            font-size: 0.75rem;
        }

        .order-details {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .order-detail-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .order-detail-item i {
            opacity: 0.7;
        }

        .order-total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .total-label {
            font-size: 0.8rem;
            color: var(--admin-text-muted);
        }

        .total-amount {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--admin-primary);
        }

        .order-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .order-actions .btn {
            flex: 1;
            padding: 8px;
            font-size: 0.8rem;
            border-radius: 10px;
        }

        /* Payment Status Badges */
        .payment-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .payment-badge.paid {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .payment-badge.pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        /* Header Actions */
        .kanban-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .kanban-stats {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .kanban-stat {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .kanban-stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .kanban-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
        }

        .kanban-stat-label {
            font-size: 0.8rem;
            color: var(--admin-text-muted);
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 4px;
        }

        .view-toggle .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            background: transparent;
            color: var(--admin-text-muted);
            transition: all 0.3s ease;
        }

        .view-toggle .btn.active {
            background: var(--admin-primary);
            color: #1a1a2e;
        }

        /* Animations */
        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .order-card {
            animation: cardEnter 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .order-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .order-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .order-card:nth-child(3) {
            animation-delay: 0.15s;
        }

        .order-card:nth-child(4) {
            animation-delay: 0.2s;
        }

        .order-card:nth-child(5) {
            animation-delay: 0.25s;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .new-order-indicator {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 10px;
            height: 10px;
            background: var(--admin-primary);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        /* Success Animation */
        @keyframes successBounce {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.3);
            }
        }

        .card-success {
            animation: successBounce 0.5s ease;
            border-color: #10b981 !important;
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .kanban-column {
                flex: 0 0 280px;
                min-width: 280px;
            }

            .kanban-header {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* ==========================================
                                                               🎯 ORDER DETAILS MODAL - PREMIUM DESIGN
                                                               ========================================== */

        .order-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .order-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .order-modal {
            background: rgba(26, 26, 46, 0.95);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            overflow: hidden;
            transform: scale(0.9) translateY(30px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5), 0 0 60px rgba(201, 162, 39, 0.15);
        }

        .order-modal-overlay.active .order-modal {
            transform: scale(1) translateY(0);
        }

        .order-modal-header {
            padding: 24px 28px;
            background: linear-gradient(135deg, rgba(201, 162, 39, 0.15), transparent);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }

        .order-modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--admin-primary), #e8c547, var(--admin-primary));
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .order-modal-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .order-modal-title h3 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
        }

        .order-modal-title .order-id {
            color: var(--admin-primary);
            font-weight: 800;
        }

        .modal-status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .modal-status-badge.pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .modal-status-badge.processing {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .modal-status-badge.shipped {
            background: rgba(139, 92, 246, 0.2);
            color: #8b5cf6;
        }

        .modal-status-badge.delivered {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .modal-status-badge.cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .modal-close-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close-btn:hover {
            background: rgba(239, 68, 68, 0.3);
            transform: rotate(90deg);
        }

        .order-modal-body {
            padding: 0;
            max-height: 60vh;
            overflow-y: auto;
        }

        .order-modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .order-modal-body::-webkit-scrollbar-thumb {
            background: rgba(201, 162, 39, 0.3);
            border-radius: 10px;
        }

        /* Modal Sections */
        .modal-section {
            padding: 20px 28px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .modal-section:last-child {
            border-bottom: none;
        }

        .modal-section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--admin-primary);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modal-section-title i {
            font-size: 1rem;
        }

        /* Order Items Grid */
        .modal-items-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .modal-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 14px;
            transition: all 0.3s ease;
        }

        .modal-item:hover {
            background: rgba(201, 162, 39, 0.08);
            border-color: rgba(201, 162, 39, 0.2);
            transform: translateX(-5px);
        }

        .modal-item-image {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .modal-item-image.placeholder {
            background: linear-gradient(135deg, var(--admin-primary), #e8c547);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a1a2e;
            font-size: 1.4rem;
        }

        .modal-item-info {
            flex: 1;
        }

        .modal-item-name {
            font-weight: 600;
            color: #fff;
            margin-bottom: 4px;
            font-size: 0.95rem;
        }

        .modal-item-name-en {
            font-size: 0.75rem;
            color: var(--admin-text-muted);
        }

        .modal-item-quantity {
            background: rgba(201, 162, 39, 0.2);
            color: var(--admin-primary);
            padding: 8px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            min-width: 50px;
            text-align: center;
        }

        .modal-item-price {
            text-align: left;
            min-width: 80px;
        }

        .modal-item-price .unit {
            font-size: 0.75rem;
            color: var(--admin-text-muted);
        }

        .modal-item-price .total {
            font-weight: 700;
            color: #fff;
        }

        /* Options/Additions Badges */
        .modal-item-options {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .option-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 6px;
            background: rgba(201, 162, 39, 0.15);
            border: 1px solid rgba(201, 162, 39, 0.3);
            color: var(--admin-primary);
        }

        .option-badge small {
            font-size: 0.65rem;
        }

        /* Customer Info Grid */
        .customer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .customer-field {
            background: rgba(255, 255, 255, 0.03);
            padding: 14px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .customer-field.full-width {
            grid-column: 1 / -1;
        }

        .customer-field-label {
            font-size: 0.75rem;
            color: var(--admin-text-muted);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .customer-field-value {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
        }

        /* Notes Section */
        .order-notes {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 15px;
            color: #f59e0b;
        }

        .order-notes i {
            margin-left: 8px;
        }

        /* Order Summary */
        .order-summary {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .summary-row.total {
            border-top: 2px solid rgba(201, 162, 39, 0.3);
            padding-top: 15px;
            margin-top: 5px;
        }

        .summary-row .label {
            color: var(--admin-text-muted);
        }

        .summary-row .value {
            font-weight: 600;
            color: #fff;
        }

        .summary-row.total .value {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--admin-primary);
        }

        .summary-row.discount .value {
            color: #10b981;
        }

        /* Modal Footer - Quick Actions */
        .order-modal-footer {
            padding: 20px 28px;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .quick-actions-title {
            font-size: 0.8rem;
            color: var(--admin-text-muted);
            margin-bottom: 12px;
            text-align: center;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .quick-action-btn {
            flex: 1;
            min-width: 100px;
            padding: 12px 16px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .quick-action-btn.pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .quick-action-btn.processing {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #fff;
        }

        .quick-action-btn.shipped {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: #fff;
        }

        .quick-action-btn.delivered {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }

        .quick-action-btn.cancelled {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        .quick-action-btn.active {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .quick-action-btn i {
            font-size: 1rem;
        }

        /* Loading State */
        .modal-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            gap: 20px;
        }

        .modal-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(201, 162, 39, 0.2);
            border-top-color: var(--admin-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 600px) {
            .order-modal {
                max-height: 95vh;
                border-radius: 20px 20px 0 0;
                margin-top: auto;
            }

            .customer-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                flex-direction: column;
            }

            .quick-action-btn {
                min-width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="kanban-header">
        <div>
            <h1 class="page-title-admin">
                <i class="bi bi-kanban me-2"></i>لوحة الطلبات
            </h1>
            <p class="page-subtitle-admin">اسحب وأفلت الطلبات لتغيير حالتها</p>
        </div>

        <div class="d-flex align-items-center gap-3">
            <!-- View Toggle -->
            <div class="view-toggle">
                <a href="{{ route('admin.orders.index') }}" class="btn">
                    <i class="bi bi-list-ul"></i>
                </a>
                <a href="{{ route('admin.orders.kanban') }}" class="btn active">
                    <i class="bi bi-kanban"></i>
                </a>
            </div>

            <!-- Refresh Button -->
            <button class="btn btn-admin-primary" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise me-2"></i>تحديث
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="kanban-stats mb-4">
        @php
            $totalOrders = collect($ordersByStatus)->flatten()->count();
            $pendingCount = count($ordersByStatus['pending']);
            // مبيعات اليوم = فقط الطلبات التي تم تسليمها اليوم
            $todayTotal = collect($ordersByStatus['delivered'])->where('updated_at', '>=', today())->sum('total');
        @endphp

        <div class="kanban-stat">
            <div class="kanban-stat-icon" style="background: rgba(201, 162, 39, 0.2); color: var(--admin-primary);">
                <i class="bi bi-box-seam"></i>
            </div>
            <div>
                <div class="kanban-stat-value">{{ $totalOrders }}</div>
                <div class="kanban-stat-label">إجمالي الطلبات</div>
            </div>
        </div>

        <div class="kanban-stat">
            <div class="kanban-stat-icon" style="background: rgba(245, 158, 11, 0.2); color: #f59e0b;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="kanban-stat-value">{{ $pendingCount }}</div>
                <div class="kanban-stat-label">بانتظار المعالجة</div>
            </div>
        </div>

        <div class="kanban-stat">
            <div class="kanban-stat-icon" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div>
                <div class="kanban-stat-value" id="todayTotalStat">{{ number_format($todayTotal) }}</div>
                <div class="kanban-stat-label">إيرادات اليوم (مسلم)</div>
            </div>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="kanban-container" id="kanbanBoard">
        @foreach ($statusConfig as $status => $config)
            <div class="kanban-column status-{{ $status }}" data-status="{{ $status }}"
                ondragover="handleDragOver(event)" ondrop="handleDrop(event, '{{ $status }}')"
                ondragleave="handleDragLeave(event)">

                <!-- Column Header -->
                <div class="kanban-column-header">
                    <h3 class="column-title">
                        <span class="column-icon">
                            <i class="{{ $config['icon'] }}"></i>
                        </span>
                        {{ $config['label'] }}
                        <span class="column-count" id="count-{{ $status }}">
                            {{ count($ordersByStatus[$status]) }}
                        </span>
                    </h3>
                </div>

                <!-- Column Body -->
                <div class="kanban-column-body" id="column-{{ $status }}">
                    @forelse($ordersByStatus[$status] as $order)
                        <div class="order-card" draggable="true" data-order-id="{{ $order->id }}"
                            data-total="{{ $order->total }}" ondragstart="handleDragStart(event, {{ $order->id }})"
                            ondragend="handleDragEnd(event)">

                            @if ($order->created_at >= now()->subMinutes(30))
                                <span class="new-order-indicator" title="طلب جديد"></span>
                            @endif

                            <!-- Card Header -->
                            <div class="order-card-header">
                                <span class="order-number">
                                    <i class="bi bi-receipt"></i>
                                    #{{ $order->order_number }}
                                </span>
                                <span class="order-time">
                                    <i class="bi bi-clock"></i>
                                    {{ $order->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Customer -->
                            <div class="order-customer">
                                <div class="customer-avatar">
                                    {{ mb_substr($order->customer_name, 0, 1) }}
                                </div>
                                <div class="customer-info">
                                    <h6>{{ $order->customer_name }}</h6>
                                    <small><i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}</small>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="order-details">
                                <span class="order-detail-item">
                                    <i class="bi bi-box"></i>
                                    {{ $order->items_count ?? $order->items->count() }} منتجات
                                </span>
                                <span class="order-detail-item payment-badge {{ $order->payment_status }}">
                                    <i class="bi bi-credit-card"></i>
                                    {{ $order->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                                </span>
                            </div>

                            <!-- Total -->
                            <div class="order-total">
                                <span class="total-label">المجموع</span>
                                <span class="total-amount">{{ number_format($order->total) }} ج.م</span>
                            </div>

                            <!-- Actions -->
                            <div class="order-actions">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-outline-primary btn-sm" onclick="printOrder({{ $order->id }})">
                                    <i class="bi bi-printer"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="kanban-empty">
                            <i class="bi bi-inbox"></i>
                            <p>لا توجد طلبات</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    <!-- Confetti Container -->
    <div id="confettiContainer"></div>

    <!-- Order Details Modal -->
    <div class="order-modal-overlay" id="orderModalOverlay" onclick="closeOrderModal(event)">
        <div class="order-modal" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="order-modal-header">
                <div class="order-modal-title">
                    <h3>طلب <span class="order-id" id="modalOrderNumber">#---</span></h3>
                    <span class="modal-status-badge" id="modalStatusBadge">---</span>
                </div>
                <button class="modal-close-btn" onclick="closeOrderModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="order-modal-body" id="modalBody">
                <!-- Loading State -->
                <div class="modal-loading" id="modalLoading">
                    <div class="modal-spinner"></div>
                    <span style="color: var(--admin-text-muted);">جاري تحميل التفاصيل...</span>
                </div>

                <!-- Content (Hidden initially) -->
                <div id="modalContent" style="display: none;">
                    <!-- Order Items Section -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>عناصر الطلب</span>
                        </div>
                        <div class="modal-items-list" id="modalItemsList">
                            <!-- Items will be injected here -->
                        </div>
                    </div>

                    <!-- Customer Info Section -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="bi bi-person-fill"></i>
                            <span>معلومات العميل</span>
                        </div>
                        <div class="customer-grid">
                            <div class="customer-field">
                                <div class="customer-field-label"><i class="bi bi-person"></i> الاسم</div>
                                <div class="customer-field-value" id="modalCustomerName">---</div>
                            </div>
                            <div class="customer-field">
                                <div class="customer-field-label"><i class="bi bi-telephone"></i> الهاتف</div>
                                <div class="customer-field-value" id="modalCustomerPhone" dir="ltr"
                                    style="text-align: right;">---</div>
                            </div>
                            <div class="customer-field full-width">
                                <div class="customer-field-label"><i class="bi bi-geo-alt"></i> العنوان</div>
                                <div class="customer-field-value" id="modalAddress">---</div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section (Conditional) -->
                    <div class="modal-section" id="modalNotesSection" style="display: none;">
                        <div class="modal-section-title">
                            <i class="bi bi-chat-left-text-fill"></i>
                            <span>ملاحظات العميل</span>
                        </div>
                        <div class="order-notes" id="modalNotes">
                            <i class="bi bi-exclamation-circle"></i>
                            <span id="modalNotesText"></span>
                        </div>
                    </div>

                    <!-- Order Summary Section -->
                    <div class="modal-section">
                        <div class="modal-section-title">
                            <i class="bi bi-receipt"></i>
                            <span>ملخص الطلب</span>
                        </div>
                        <div class="order-summary">
                            <div class="summary-row">
                                <span class="label">المجموع الفرعي</span>
                                <span class="value" id="modalSubtotal">---</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">التوصيل</span>
                                <span class="value" id="modalShipping">---</span>
                            </div>
                            <div class="summary-row discount" id="modalDiscountRow" style="display: none;">
                                <span class="label"><i class="bi bi-tag"></i> الخصم <span
                                        id="modalCouponCode"></span></span>
                                <span class="value" id="modalDiscount">---</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">طريقة الدفع</span>
                                <span class="value" id="modalPaymentMethod">---</span>
                            </div>
                            <div class="summary-row">
                                <span class="label">حالة الدفع</span>
                                <span class="value" id="modalPaymentStatus">---</span>
                            </div>
                            <div class="summary-row total">
                                <span class="label">الإجمالي</span>
                                <span class="value" id="modalTotal">---</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer - Quick Actions -->
            <div class="order-modal-footer">
                <div class="quick-actions-title">تغيير سريع للحالة</div>
                <div class="quick-actions" id="quickActions">
                    <button class="quick-action-btn pending" data-status="pending"
                        onclick="quickStatusChange('pending')">
                        <i class="bi bi-hourglass-split"></i>
                        <span>انتظار</span>
                    </button>
                    <button class="quick-action-btn processing" data-status="processing"
                        onclick="quickStatusChange('processing')">
                        <i class="bi bi-gear-fill"></i>
                        <span>تحضير</span>
                    </button>
                    <button class="quick-action-btn shipped" data-status="shipped"
                        onclick="quickStatusChange('shipped')">
                        <i class="bi bi-truck"></i>
                        <span>شحن</span>
                    </button>
                    <button class="quick-action-btn delivered" data-status="delivered"
                        onclick="quickStatusChange('delivered')">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>تسليم</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ==========================================
        // 🎮 KANBAN DRAG & DROP FUNCTIONALITY
        // ==========================================

        let draggedElement = null;
        let draggedOrderId = null;

        // Drag Start
        function handleDragStart(event, orderId) {
            draggedElement = event.target;
            draggedOrderId = orderId;

            event.target.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', orderId);

            // Play pickup sound
            playSound('pickup');
        }

        // Drag End
        function handleDragEnd(event) {
            event.target.classList.remove('dragging');

            // Remove drag-over from all columns
            document.querySelectorAll('.kanban-column').forEach(col => {
                col.classList.remove('drag-over');
            });
        }

        // Drag Over
        function handleDragOver(event) {
            event.preventDefault();
            event.dataTransfer.dropEffect = 'move';

            const column = event.target.closest('.kanban-column');
            if (column && !column.classList.contains('drag-over')) {
                // Remove from others
                document.querySelectorAll('.kanban-column').forEach(col => {
                    col.classList.remove('drag-over');
                });
                column.classList.add('drag-over');
            }
        }

        // Drag Leave
        function handleDragLeave(event) {
            const column = event.target.closest('.kanban-column');
            if (column && !column.contains(event.relatedTarget)) {
                column.classList.remove('drag-over');
            }
        }

        // Drop
        async function handleDrop(event, newStatus) {
            event.preventDefault();

            const column = event.target.closest('.kanban-column');
            if (column) {
                column.classList.remove('drag-over');
            }

            const orderId = event.dataTransfer.getData('text/plain');
            const orderCard = document.querySelector(`[data-order-id="${orderId}"]`);
            const currentStatus = orderCard.closest('.kanban-column').dataset.status;

            // Don't do anything if dropped in same column
            if (currentStatus === newStatus) {
                return;
            }

            // Move card visually first (optimistic update)
            const targetColumn = document.querySelector(`#column-${newStatus}`);
            const emptyMessage = targetColumn.querySelector('.kanban-empty');
            if (emptyMessage) {
                emptyMessage.remove();
            }
            targetColumn.prepend(orderCard);

            // Add success animation
            orderCard.classList.add('card-success');
            setTimeout(() => orderCard.classList.remove('card-success'), 500);

            // Update counts
            updateColumnCounts(currentStatus, newStatus);

            // Play appropriate sound
            if (newStatus === 'delivered') {
                playSound('success');
                createConfetti();
                // تحديث إيرادات اليوم فورياً
                updateTodayTotal(orderCard, 'add');
                // تحديث badge حالة الدفع لـ مدفوع
                updatePaymentBadge(orderCard, 'paid');
            } else if (currentStatus === 'delivered') {
                // إذا تم نقل الطلب من التوصيل لحالة أخرى - طرح المبلغ
                updateTodayTotal(orderCard, 'subtract');
                // تحديث badge حالة الدفع لـ غير مدفوع
                updatePaymentBadge(orderCard, 'pending');
            } else if (newStatus === 'cancelled') {
                playSound('cancel');
            } else {
                playSound('drop');
            }

            // Send AJAX request
            try {
                const response = await fetch(`/admin/orders/${orderId}/status-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Show toast notification
                    showKanbanToast(data.message, 'success');
                } else {
                    // Revert on error
                    document.querySelector(`#column-${currentStatus}`).prepend(orderCard);
                    updateColumnCounts(newStatus, currentStatus);
                    showKanbanToast('حدث خطأ أثناء تحديث الطلب', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                // Revert on error
                document.querySelector(`#column-${currentStatus}`).prepend(orderCard);
                updateColumnCounts(newStatus, currentStatus);
                showKanbanToast('حدث خطأ في الاتصال', 'error');
            }
        }

        // Update column counts
        function updateColumnCounts(fromStatus, toStatus) {
            const fromCount = document.getElementById(`count-${fromStatus}`);
            const toCount = document.getElementById(`count-${toStatus}`);

            if (fromCount) {
                fromCount.textContent = parseInt(fromCount.textContent) - 1;
            }
            if (toCount) {
                toCount.textContent = parseInt(toCount.textContent) + 1;
            }

            // Check if source column is now empty
            const fromColumn = document.querySelector(`#column-${fromStatus}`);
            if (fromColumn && fromColumn.querySelectorAll('.order-card').length === 0) {
                fromColumn.innerHTML = `
                <div class="kanban-empty">
                    <i class="bi bi-inbox"></i>
                    <p>لا توجد طلبات</p>
                </div>
            `;
            }
        }

        // تحديث إيرادات اليوم فورياً
        function updateTodayTotal(orderCard, action) {
            const todayTotalEl = document.getElementById('todayTotalStat');
            if (!todayTotalEl) return;

            const orderTotal = parseFloat(orderCard.dataset.total) || 0;
            const currentTotal = parseFloat(todayTotalEl.textContent.replace(/,/g, '')) || 0;

            let newTotal;
            if (action === 'add') {
                newTotal = currentTotal + orderTotal;
            } else {
                newTotal = Math.max(0, currentTotal - orderTotal);
            }

            // تحديث القيمة مع animation
            todayTotalEl.style.transform = 'scale(1.3)';
            todayTotalEl.style.color = action === 'add' ? '#10b981' : '#ef4444';
            todayTotalEl.textContent = newTotal.toLocaleString();

            setTimeout(() => {
                todayTotalEl.style.transform = 'scale(1)';
                todayTotalEl.style.color = '';
            }, 300);
        }

        // تحديث badge حالة الدفع فورياً
        function updatePaymentBadge(orderCard, status) {
            const badge = orderCard.querySelector('.payment-badge');
            if (!badge) return;

            // تحديث الـ class والنص
            badge.className = 'order-detail-item payment-badge ' + status;
            badge.innerHTML = `<i class="bi bi-credit-card"></i> ${status === 'paid' ? 'مدفوع' : 'غير مدفوع'}`;

            // Animation
            badge.style.transform = 'scale(1.2)';
            badge.style.transition = 'transform 0.3s ease';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 300);
        }

        // ==========================================
        // 🔊 SOUND EFFECTS
        // ==========================================

        const sounds = {
            pickup: 'https://assets.mixkit.co/active_storage/sfx/2571/2571-preview.mp3',
            drop: 'https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3',
            success: 'https://assets.mixkit.co/active_storage/sfx/2018/2018-preview.mp3',
            cancel: 'https://assets.mixkit.co/active_storage/sfx/2570/2570-preview.mp3'
        };

        function playSound(type) {
            if (typeof NotificationSystem !== 'undefined' && !NotificationSystem.soundEnabled) {
                return;
            }

            const audio = new Audio(sounds[type]);
            audio.volume = 0.3;
            audio.play().catch(() => {});
        }

        // ==========================================
        // 🎉 CONFETTI EFFECT
        // ==========================================

        function createConfetti() {
            const container = document.getElementById('confettiContainer');
            container.style.cssText = `
            position: fixed;
            inset: 0;
            z-index: 9999;
            pointer-events: none;
            overflow: hidden;
        `;

            const colors = ['#C9A227', '#E8C547', '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6'];

            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                position: absolute;
                width: ${Math.random() * 10 + 5}px;
                height: ${Math.random() * 10 + 5}px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                left: ${Math.random() * 100}vw;
                top: -20px;
                border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                transform: rotate(${Math.random() * 360}deg);
                animation: confettiFall ${2 + Math.random() * 2}s linear forwards;
                animation-delay: ${Math.random() * 0.5}s;
            `;
                container.appendChild(confetti);
            }

            // Add animation
            if (!document.getElementById('confettiStyles')) {
                const style = document.createElement('style');
                style.id = 'confettiStyles';
                style.textContent = `
                @keyframes confettiFall {
                    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                    100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
                }
            `;
                document.head.appendChild(style);
            }

            // Clear after animation
            setTimeout(() => {
                container.innerHTML = '';
            }, 4000);
        }

        // ==========================================
        // 📢 TOAST NOTIFICATIONS
        // ==========================================

        function showKanbanToast(message, type = 'success') {
            if (typeof NotificationSystem !== 'undefined') {
                NotificationSystem.showToast({
                    title: type === 'success' ? 'تم ✓' : 'خطأ',
                    message: message,
                    type: type === 'success' ? 'order' : 'warning'
                });
            }
        }

        // ==========================================
        // 🖨️ PRINT ORDER
        // ==========================================

        function printOrder(orderId) {
            window.open(`/admin/orders/${orderId}?print=1`, '_blank', 'width=800,height=600');
        }

        // ==========================================
        // 🎯 ORDER DETAILS MODAL
        // ==========================================

        let currentModalOrderId = null;
        let currentModalStatus = null;

        // Open Modal and fetch order details
        async function openOrderModal(orderId) {
            currentModalOrderId = orderId;

            // Show modal with loading state
            const modal = document.getElementById('orderModalOverlay');
            const loading = document.getElementById('modalLoading');
            const content = document.getElementById('modalContent');

            modal.classList.add('active');
            loading.style.display = 'flex';
            content.style.display = 'none';

            // Play sound
            playSound('pickup');

            // Fetch order details
            try {
                const response = await fetch(`/admin/orders/${orderId}/details-ajax`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    populateModal(data.order);
                    loading.style.display = 'none';
                    content.style.display = 'block';
                } else {
                    closeOrderModal();
                    showKanbanToast('حدث خطأ في تحميل بيانات الطلب', 'error');
                }
            } catch (error) {
                console.error('Error fetching order:', error);
                closeOrderModal();
                showKanbanToast('حدث خطأ في الاتصال', 'error');
            }
        }

        // Close Modal
        function closeOrderModal(event) {
            if (event && event.target !== event.currentTarget) return;

            const modal = document.getElementById('orderModalOverlay');
            modal.classList.remove('active');
            currentModalOrderId = null;
            currentModalStatus = null;
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeOrderModal();
            }
        });

        // Populate modal with order data
        function populateModal(order) {
            currentModalStatus = order.status;

            // Header
            document.getElementById('modalOrderNumber').textContent = '#' + order.order_number;

            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.textContent = order.status_ar;
            statusBadge.className = 'modal-status-badge ' + order.status;

            // Order Items
            const itemsList = document.getElementById('modalItemsList');
            itemsList.innerHTML = order.items.map(item => `
                <div class="modal-item">
                    ${item.image 
                        ? `<img src="${item.image}" class="modal-item-image" alt="${item.name}">`
                        : `<div class="modal-item-image placeholder"><i class="bi bi-box"></i></div>`
                    }
                    <div class="modal-item-info">
                        <div class="modal-item-name">${item.name}</div>
                        ${item.options && item.options.length > 0 ? `
                                        <div class="modal-item-options">
                                            ${item.options.filter(opt => opt.value && opt.value !== 'null' && opt.value !== '').map(opt => `
                                    <span class="option-badge">
                                        ${opt.label}: ${opt.value}
                                        ${opt.price ? ` <small class="text-success">(${opt.price})</small>` : ''}
                                    </span>
                                `).join('')}
                                        </div>
                                    ` : ''}
                    </div>
                    <div class="modal-item-quantity">×${item.quantity}</div>
                    <div class="modal-item-price">
                        <div class="unit">${item.price} ج.م</div>
                        <div class="total">${item.total} ج.م</div>
                    </div>
                </div>
            `).join('');

            // Customer Info
            document.getElementById('modalCustomerName').textContent = order.customer_name;
            document.getElementById('modalCustomerPhone').textContent = order.customer_phone;

            let address = order.shipping_address || '';
            if (order.city) address += (address ? '، ' : '') + order.city;
            if (order.governorate) address += ' / ' + order.governorate;
            document.getElementById('modalAddress').textContent = address || 'غير محدد';

            // Notes
            const notesSection = document.getElementById('modalNotesSection');
            if (order.notes) {
                notesSection.style.display = 'block';
                document.getElementById('modalNotesText').textContent = order.notes;
            } else {
                notesSection.style.display = 'none';
            }

            // Order Summary
            document.getElementById('modalSubtotal').textContent = order.subtotal + ' ج.م';
            document.getElementById('modalShipping').textContent = order.shipping;

            const discountRow = document.getElementById('modalDiscountRow');
            if (order.discount) {
                discountRow.style.display = 'flex';
                document.getElementById('modalDiscount').textContent = '- ' + order.discount + ' ج.م';
                if (order.coupon_code) {
                    document.getElementById('modalCouponCode').textContent = '(' + order.coupon_code + ')';
                }
            } else {
                discountRow.style.display = 'none';
            }

            document.getElementById('modalPaymentMethod').textContent = order.payment_method;

            const paymentStatusEl = document.getElementById('modalPaymentStatus');
            paymentStatusEl.textContent = order.payment_status === 'paid' ? '✓ مدفوع' : '○ غير مدفوع';
            paymentStatusEl.style.color = order.payment_status === 'paid' ? '#10b981' : '#f59e0b';

            document.getElementById('modalTotal').textContent = order.total + ' ج.م';

            // Update quick action buttons - highlight current status
            document.querySelectorAll('.quick-action-btn').forEach(btn => {
                const btnStatus = btn.dataset.status;
                if (btnStatus === order.status) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Quick status change from modal
        async function quickStatusChange(newStatus) {
            if (!currentModalOrderId || newStatus === currentModalStatus) return;

            const oldStatus = currentModalStatus;

            // Optimistic UI update
            currentModalStatus = newStatus;
            document.querySelectorAll('.quick-action-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.status === newStatus);
            });

            const statusLabels = {
                'pending': 'طلب جديد',
                'processing': 'قيد التحضير',
                'shipped': 'في الطريق',
                'delivered': 'تم التوصيل',
                'cancelled': 'ملغي'
            };

            const statusBadge = document.getElementById('modalStatusBadge');
            statusBadge.textContent = statusLabels[newStatus];
            statusBadge.className = 'modal-status-badge ' + newStatus;

            // Play sound
            if (newStatus === 'delivered') {
                playSound('success');
                createConfetti();
            } else if (newStatus === 'cancelled') {
                playSound('cancel');
            } else {
                playSound('drop');
            }

            // Update Kanban board
            const orderCard = document.querySelector(`[data-order-id="${currentModalOrderId}"]`);
            if (orderCard) {
                const targetColumn = document.querySelector(`#column-${newStatus}`);
                const emptyMessage = targetColumn.querySelector('.kanban-empty');
                if (emptyMessage) emptyMessage.remove();

                orderCard.classList.add('card-success');
                setTimeout(() => orderCard.classList.remove('card-success'), 500);

                targetColumn.prepend(orderCard);
                updateColumnCounts(oldStatus, newStatus);
            }

            // Send AJAX request
            try {
                const response = await fetch(`/admin/orders/${currentModalOrderId}/status-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showKanbanToast(data.message, 'success');
                } else {
                    // Revert on error
                    currentModalStatus = oldStatus;
                    showKanbanToast('حدث خطأ أثناء تحديث الطلب', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                currentModalStatus = oldStatus;
                showKanbanToast('حدث خطأ في الاتصال', 'error');
            }
        }

        // Add click handler to order cards
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-card').forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't open modal if clicking on action buttons
                    if (e.target.closest('.order-actions') || e.target.closest('a') || e.target
                        .closest('button')) {
                        return;
                    }

                    const orderId = this.dataset.orderId;
                    openOrderModal(orderId);
                });
            });
        });
    </script>
@endpush
