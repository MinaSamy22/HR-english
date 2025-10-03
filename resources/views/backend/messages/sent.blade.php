@extends('backend.layouts.app')

@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-paper-plane mr-2"></i>{{ __('h_message.sent_messages') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('h_message.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('h_message.sent_messages') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">
                                    <i class="fas fa-paper-plane mr-1"></i>
                                    {{ __('h_message.sent_messages') }}
                                </h3>
                                <div class="d-flex align-items-center">
                                    <div class="btn-group mr-2">
                                        <button type="button" class="btn btn-sm btn-secondary" id="view-table">
                                            <i class="fas fa-table"></i> {{ __('h_message.table') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="view-cards">
                                            <i class="fas fa-th-large"></i> {{ __('h_message.cards') }}
                                        </button>
                                    </div>
                                    <div class="card-tools">
                                        <a href="{{ route('messages.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> {{ __('h_message.compose_new') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($messages->count() > 0)
                                <!-- Table View -->
                                <div id="table-view" class="table-responsive mailbox-messages">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-users mr-1"></i>{{ __('h_message.recipients') }}</th>
                                                <th><i class="fas fa-envelope mr-1"></i>{{ __('h_message.subject') }}</th>
                                                <th><i class="fas fa-calendar mr-1"></i>{{ __('h_message.date') }}</th>
                                                <th><i class="fas fa-cog mr-1"></i>{{ __('h_message.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($messages as $message)
                                                <tr class="clickable-row"
                                                    data-href="{{ route('messages.show', $message) }}"
                                                    style="cursor: pointer;">

                                                    <td class="mailbox-name">
                                                        <i class="fas fa-user-circle mr-1 text-primary"></i>
                                                        @php
                                                            $recipientNames = $message->recipient_names ?? [];
                                                            $recipientCount = $message->recipient_count ?? 0;
                                                        @endphp
                                                        <span title="{{ is_array($recipientNames) ? implode(', ', $recipientNames) : '' }}">
                                                            {{ $recipientCount > 1 ? $recipientCount . ' ' . __('h_message.recipients_count') : ($recipientNames[0] ?? __('h_message.no_recipients')) }}
                                                        </span>
                                                    </td>
                                                    <td class="mailbox-subject">
                                                        <span class="text-dark">
                                                            <i class="fas fa-envelope-open-text mr-1 text-info"></i>
                                                            {{ Str::limit($message->subject, 50) }}
                                                            @if($message->is_urgent)
                                                                <span class="badge badge-warning ml-1">
                                                                    <i class="fas fa-exclamation-triangle"></i> {{ __('h_message.urgent') }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="mailbox-date">
                                                        <i class="far fa-clock mr-1 text-secondary"></i>
                                                        {{ $message->created_at->diffForHumans() }}
                                                    </td>
                                                    <td class="mailbox-actions" onclick="event.stopPropagation();">

                                                        <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('h_message.are_you_sure_delete') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ __('h_message.delete') }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Cards View -->
                                <div id="cards-view" class="p-3" style="display: none;">
                                    <div class="row">
                                        @foreach($messages as $message)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card message-card clickable-card"
                                                     data-href="{{ route('messages.show', $message) }}"
                                                     style="cursor: pointer;">
                                                    <div class="card-header bg-light p-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                @if($message->is_urgent)
                                                                    <i class="fas fa-star text-warning mr-1" title="{{ __('h_message.urgent') }}"></i>
                                                                @endif
                                                                <i class="fas fa-user-circle mr-1 text-primary"></i>
                                                                <small class="font-weight-bold">
                                                                    @php
                                                                        $recipientNames = $message->recipient_names ?? [];
                                                                        $recipientCount = $message->recipient_count ?? 0;
                                                                    @endphp
                                                                    {{ $recipientCount > 1 ? $recipientCount . ' ' . __('h_message.recipients_count') : ($recipientNames[0] ?? __('h_message.no_recipients')) }}
                                                                </small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-calendar-alt mr-1 text-secondary"></i>
                                                                <small>{{ $message->created_at->format('M d') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title font-weight-bold">
                                                            <i class="fas fa-envelope mr-1 text-info"></i>
                                                            {{ Str::limit($message->subject, 40) }}
                                                            @if($message->is_urgent)
                                                                <span class="badge badge-warning badge-sm ml-1">
                                                                    <i class="fas fa-exclamation-triangle"></i> {{ __('h_message.urgent') }}
                                                                </span>
                                                            @endif
                                                        </h6>
                                                        <p class="card-text text-muted small">
                                                            <i class="fas fa-align-left mr-1"></i>
                                                            {{ Str::limit(strip_tags($message->content ?? __('h_message.no_content_preview')), 80) }}
                                                        </p>
                                                        @if($recipientCount > 1)
                                                            <div class="mb-2">
                                                                <small class="text-info">
                                                                    <i class="fas fa-users"></i>
                                                                    {{ $recipientCount }} {{ __('h_message.recipients_count') }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="far fa-clock mr-1"></i>
                                                                {{ $message->created_at->diffForHumans() }}
                                                            </small>
                                                            <div onclick="event.stopPropagation();">
                                                                <form action="{{ route('messages.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('h_message.are_you_sure_delete') }}')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" title="{{ __('h_message.delete') }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="text-center p-4">
                                    <i class="fas fa-paper-plane fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('h_message.no_messages_sent') }}</h4>
                                    <p class="text-muted">{{ __('h_message.start_composing') }}</p>
                                    <a href="{{ route('messages.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> {{ __('h_message.compose_message') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        @if($messages->hasPages())
                            <div class="card-footer clearfix">
                                {{ $messages->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.clickable-row, .clickable-card {
    transition: all 0.2s ease;
}

.clickable-row:hover {
    background-color: #f8f9fa !important;
}

.clickable-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.message-card {
    transition: all 0.2s ease;
    height: 100%;
}

.mailbox-actions {
    position: relative;
    z-index: 10;
}

.small-box {
    border-radius: 0.5rem;
}

/* Ensure proper spacing for action buttons in cards */
.card-body .btn-group {
    white-space: nowrap;
}

/* Icon spacing in table */
.table thead th i {
    color: #6c757d;
}

.mailbox-star {
    width: 40px;
    text-align: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewTableBtn = document.getElementById('view-table');
    const viewCardsBtn = document.getElementById('view-cards');
    const tableView = document.getElementById('table-view');
    const cardsView = document.getElementById('cards-view');

    // Default to table view
    tableView.style.display = 'block';
    cardsView.style.display = 'none';

    viewTableBtn.addEventListener('click', function() {
        tableView.style.display = 'block';
        cardsView.style.display = 'none';
        viewTableBtn.classList.remove('btn-outline-secondary');
        viewTableBtn.classList.add('btn-secondary');
        viewCardsBtn.classList.remove('btn-secondary');
        viewCardsBtn.classList.add('btn-outline-secondary');
    });

    viewCardsBtn.addEventListener('click', function() {
        cardsView.style.display = 'block';
        tableView.style.display = 'none';
        viewCardsBtn.classList.remove('btn-outline-secondary');
        viewCardsBtn.classList.add('btn-secondary');
        viewTableBtn.classList.remove('btn-secondary');
        viewTableBtn.classList.add('btn-outline-secondary');
    });

    // Handle clickable rows (table view)
    const clickableRows = document.querySelectorAll('.clickable-row');
    clickableRows.forEach(function(row) {
        row.addEventListener('click', function() {
            const href = this.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        });
    });

    // Handle clickable cards (card view)
    const clickableCards = document.querySelectorAll('.clickable-card');
    clickableCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const href = this.getAttribute('data-href');
            if (href) {
                window.location.href = href;
            }
        });
    });
});
</script>
@endsection
