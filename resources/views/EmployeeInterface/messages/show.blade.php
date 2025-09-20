@extends('EmployeeInterface.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ __('E_message.view_message') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('employee.messages.inbox') }}">{{ __('E_message.messages') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('E_message.view_message') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                @if($message->is_urgent)
                                    <i class="fas fa-star text-warning mr-1"></i>
                                @endif
                                {{ $message->subject }}
                                @if($message->is_urgent)
                                    <span class="badge badge-warning ml-2">{{ __('E_message.urgent') }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="mailbox-read-info">
                                <h6>
                                    {{ __('E_message.from') }}: {{ $message->sender->name ?? __('E_message.unknown_sender') }}
                                    <span class="mailbox-read-time float-right">{{ $message->created_at->format('d M. Y H:i') }}</span>
                                </h6>
                                <h6>
                                    {{ __('E_message.to') }}: <span class="badge badge-secondary">{{ auth()->user()->name }}</span>
                                    @if($message->recipient_count > 1)
                                        <small class="text-muted">{{ __('E_message.and_others', ['count' => $message->recipient_count - 1]) }}</small>
                                    @endif
                                </h6>
                            </div>
                            <div class="mailbox-read-message">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="float-left">
                                <a href="{{ route('employee.messages.inbox') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> {{ __('E_message.back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('E_message.message_details') }}</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> {{ __('E_message.received_date') }}</strong>
                            <p class="text-muted">{{ $message->created_at->format('F d, Y') }} {{ __('E_message.at') }} {{ $message->created_at->format('H:i') }}</p>
                            <hr>

                            <strong><i class="fas fa-user mr-1"></i> {{ __('E_message.from') }}</strong>
                            <p class="text-muted">{{ $message->sender->name ?? __('E_message.unknown_sender') }}</p>
                            <hr>

                            <strong><i class="fas fa-info-circle mr-1"></i> {{ __('E_message.priority') }}</strong>
                            <p class="text-muted">
                                @if($message->is_urgent)
                                    <span class="badge badge-warning">{{ __('E_message.urgent') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('E_message.normal') }}</span>
                                @endif
                            </p>

                            @if($message->isReadBy(auth()->id()))
                                <hr>
                                <strong><i class="fas fa-eye mr-1"></i> {{ __('E_message.read_status') }}</strong>
                                <p class="text-muted">
                                    <span class="badge badge-success">{{ __('E_message.read') }}</span><br>
                                    <small>{{ $message->getReadTime(auth()->id())?->format('M d, Y H:i') }}</small>
                                </p>
                            @endif

                            @if($message->recipient_count > 1)
                                <hr>
                                <strong><i class="fas fa-users mr-1"></i> {{ __('E_message.recipients') }}</strong>
                                <p class="text-muted">
                                    {{ __('E_message.sent_to_count', ['count' => $message->recipient_count]) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
