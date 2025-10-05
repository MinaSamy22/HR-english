@extends('admins.layouts.admin-layout')

@section('title', 'Admin Portal - All Companies')

@section('page_title')
    <i class="fas fa-building"></i>
    {{ __('Admin-Interface.manage_companies') }}
@endsection

@section('styles')
<style>
    .companies-content {
        padding: 20px 0;
    }

    .companies-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .companies-card-header {
        background: white;
        color: #1e293b;
        padding: 24px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .companies-card-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #1e293b;
    }

    .companies-card-header h2 i {
        color: #3b82f6;
    }

    .btn-back {
        background: #f8fafc;
        color: #475569;
        padding: 10px 20px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #e2e8f0;
        border-color: #cbd5e0;
        transform: translateX(-5px);
    }

    [dir="rtl"] .btn-back:hover {
        transform: translateX(5px);
    }

    .companies-card-body {
        padding: 24px;
        background: white;
    }

    .lead {
        color: #64748b;
        font-size: 16px;
        margin-bottom: 24px;
    }

    /* Table Styles */
    .companies-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .companies-table thead {
        background: #f8fafc;
    }

    .companies-table thead th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #334155;
        border-bottom: 2px solid #e2e8f0;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    [dir="rtl"] .companies-table thead th {
        text-align: right;
    }

    .companies-table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .companies-table tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.01);
    }

    .companies-table tbody tr:last-child {
        border-bottom: none;
    }

    .companies-table tbody td {
        padding: 16px;
        color: #475569;
        font-size: 14px;
    }

    .companies-table tbody td:first-child {
        font-weight: 500;
        color: #1e293b;
    }

    /* Delete Button */
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    }

    .btn-delete:active {
        transform: translateY(0);
    }

    .btn-delete i {
        font-size: 14px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 18px;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .companies-card-header {
            flex-direction: column;
            gap: 12px;
            align-items: flex-start;
        }

        .companies-table {
            font-size: 12px;
        }

        .companies-table thead th,
        .companies-table tbody td {
            padding: 12px 8px;
        }

        .companies-card-header h2 {
            font-size: 20px;
        }

        .btn-delete {
            padding: 6px 12px;
            font-size: 12px;
        }
    }
</style>
@endsection

@section('content')
<div class="companies-content">
    <div class="companies-card">
        <div class="companies-card-header">
            <h2>
                <i class="fas fa-building"></i>
                {{ __('Admin-Interface.all_companies') ?? 'All Companies' }}
            </h2>
            <a href="{{ route('admin.landing') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                {{ __('Admin-Interface.back') ?? 'Back' }}
            </a>
        </div>
        <div class="companies-card-body">
            <p class="lead">{{ __('Admin-Interface.manage_delete_companies') ?? 'Manage and delete companies' }}</p>

            @if($companies->count() > 0)
                <!-- Table to display companies -->
                <table class="companies-table">
                    <thead>
                        <tr>
                            <th>{{ __('Admin-Interface.company_name') ?? 'Company Name' }}</th>
                            <th>{{ __('Admin-Interface.actions') ?? 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>{{ $company->name }}</td>
                                <td>
                                    <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" onclick="return confirm('{{ __('Admin-Interface.confirm_delete_company') ?? 'Are you sure you want to delete this company?' }}');">
                                            <i class="fas fa-trash-alt"></i>
                                            {{ __('Admin-Interface.delete') ?? 'Delete' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-building"></i>
                    <p>{{ __('Admin-Interface.no_companies_found') ?? 'No companies found' }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add smooth animations for table rows
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.companies-table tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 50);
        });
    });
</script>
@endsection
