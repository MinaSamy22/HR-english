@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('E_message.my_messages') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('employee/dashboard') }}">{{ __('E_message.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('E_message.messages') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
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
                                    <i class="fas fa-inbox mr-1"></i>
                                    {{ __('E_message.inbox') }}
                                </h3>
                                <div class="card-tools">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="view-table">
                                            <i class="fas fa-table"></i> {{ __('E_message.table') }}
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" id="view-cards">
                                            <i class="fas fa-th-large"></i> {{ __('E_message.cards') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($messages->count() > 0)
                                <!-- Table View -->
                                <div id="table-view" class="table-responsive mailbox-messages" style="display: none;">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>{{ __('E_message.from') }}</th>
                                                <th>{{ __('E_message.subject') }}</th>
                                                <th>{{ __('E_message.date') }}</th>
                                                <th>{{ __('E_message.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($messages as $message)
                                                <tr class="clickable-row {{ $message->is_read_by_me ? '' : 'font-weight-bold' }}"
                                                    data-href="{{ route('employee.messages.show', $message) }}"
                                                    style="cursor: pointer;">
                                                    <td class="mailbox-star">
                                                        @if(!$message->is_read_by_me)
                                                            <i class="fas fa-circle text-primary" style="font-size: 8px;" title="{{ __('E_message.unread') }}"></i>
                                                        @endif
                                                        @if($message->is_urgent)
                                                            <i class="fas fa-star text-warning ml-1" title="{{ __('E_message.urgent') }}"></i>
                                                        @endif
                                                    </td>
                                                    <td class="mailbox-name">
                                                        <span>{{ $message->sender->name ?? __('E_message.unknown_sender') }}</span>
                                                    </td>
                                                    <td class="mailbox-subject">
                                                        <span class="text-dark">
                                                            {{ Str::limit($message->subject, 50) }}
                                                            @if($message->is_urgent)
                                                                <span class="badge badge-warning ml-1">{{ __('E_message.urgent') }}</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="mailbox-date">
                                                        {{ $message->created_at->format('M d, Y H:i') }}
                                                    </td>
                                                    <td class="mailbox-actions" onclick="event.stopPropagation();">
                                                        <a href="{{ route('employee.messages.show', $message) }}" class="btn btn-default btn-sm" title="{{ __('E_message.view') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Cards View -->
                                <div id="cards-view" class="p-3">
                                    <div class="row">
                                        @foreach($messages as $message)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card message-card {{ $message->is_read_by_me ? '' : 'border-primary' }} clickable-card"
                                                     data-href="{{ route('employee.messages.show', $message) }}"
                                                     style="cursor: pointer;">
                                                    <div class="card-header {{ $message->is_read_by_me ? 'bg-light' : 'bg-primary text-white' }} p-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                @if(!$message->is_read_by_me)
                                                                    <i class="fas fa-circle text-warning mr-1" style="font-size: 8px;" title="{{ __('E_message.unread') }}"></i>
                                                                @endif
                                                                <small class="font-weight-bold">
                                                                    {{ $message->sender->name ?? __('E_message.unknown_sender') }}
                                                                </small>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                @if($message->is_urgent)
                                                                    <i class="fas fa-star text-warning mr-1" title="{{ __('E_message.urgent') }}"></i>
                                                                @endif
                                                                <small>{{ $message->created_at->format('M d') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-3">
                                                        <h6 class="card-title {{ $message->is_read_by_me ? '' : 'font-weight-bold' }}">
                                                            {{ Str::limit($message->subject, 40) }}
                                                            @if($message->is_urgent)
                                                                <span class="badge badge-warning badge-sm ml-1">{{ __('E_message.urgent') }}</span>
                                                            @endif
                                                        </h6>
                                                        <p class="card-text text-muted small">
                                                            {{ Str::limit(strip_tags($message->content), 80) }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                {{ $message->created_at->diffForHumans() }}
                                                            </small>
                                                            <div onclick="event.stopPropagation();">
                                                                <a href="{{ route('employee.messages.show', $message) }}"
                                                                   class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-eye"></i> {{ __('E_message.view') }}
                                                                </a>
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
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">{{ __('E_message.no_messages_title') }}</h4>
                                    <p class="text-muted">{{ __('E_message.no_messages_desc') }}</p>
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

.message-card.border-primary {
    border-width: 2px;
}

.mailbox-actions {
    position: relative;
    z-index: 10;
}

.clickable-row.font-weight-bold {
    font-weight: 600 !important;
}

.clickable-row.font-weight-bold:hover {
    background-color: #e3f2fd !important;
}

.small-box {
    border-radius: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewTableBtn = document.getElementById('view-table');
    const viewCardsBtn = document.getElementById('view-cards');
    const tableView = document.getElementById('table-view');
    const cardsView = document.getElementById('cards-view');

    // Default to cards view
    cardsView.style.display = 'block';
    tableView.style.display = 'none';

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

    // Mark as read functionality
    $('.mark-read-btn').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        var messageId = $(this).data('message-id');
        var button = $(this);
        var container = button.closest('.clickable-row, .clickable-card');

        $.post('/employee/messages/' + messageId + '/mark-read', {
            _token: '{{ csrf_token() }}'
        })
        .done(function(response) {
            if (response.success) {
                // Remove unread styling
                container.removeClass('font-weight-bold border-primary');
                container.find('.bg-primary').removeClass('bg-primary text-white').addClass('bg-light');
                container.find('.badge-primary').remove();
                container.find('.fa-circle').remove();
                button.remove();
                updateUnreadCount();
            }
        })
        .fail(function() {
            alert('Error marking message as read. Please try again.');
        });
    });

    function updateUnreadCount() {
        var currentCount = parseInt($('.navbar-badge').text()) || 0;
        if (currentCount > 1) {
            $('.navbar-badge').text(currentCount - 1);
        } else {
            $('.navbar-badge').remove();
        }
    }
});
</script>
@endsection
