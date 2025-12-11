@extends('backend.layouts.app')

@section('content')
<div class="content-wrapper" style="background-image: url('{{ asset('/dist/img/dashboard.jpg') }}'); background-size: cover; background-position: center;">
    <div class="content-header">
        <div class="container-fluid">
            <div class=" mb-2 d-flex justify-content-between">
                <div class="">
                    <h1 class="m-0">{{ __('h_message.view_message') }}</h1>
                </div>
                <div class="">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            @if(auth()->user()->is_role == 1)
                                <a href="{{ route('messages.sent') }}">{{ __('h_message.sent_messages') }}</a>
                            @else
                                <a href="{{ route('messages.inbox') }}">{{ __('h_message.inbox') }}</a>
                            @endif
                        </li>
                        <li class="breadcrumb-item active">{{ __('h_message.view_message') }}</li>
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
                                    <span class="badge badge-warning ml-2">{{ __('h_message.urgent') }}</span>
                                @endif
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="mailbox-read-info">
                                <h6>
                                    {{ __('h_message.from') }}: {{ $message->sender->name ?? __('h_message.unknown_sender') }}
                                    <span class="mailbox-read-time float-right">{{ $message->created_at->format('d M. Y H:i') }}</span>
                                </h6>
                                <h6>
                                    {{ __('h_message.to') }}:
                                    @foreach($message->recipients() as $recipient)
                                        <span class="badge badge-secondary mr-1">{{ $recipient->name }}</span>
                                    @endforeach
                                </h6>
                            </div>
                            <div class="mailbox-read-message">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="float-left">
                                @if(auth()->user()->is_role == 1)
                                    <a href="{{ route('messages.sent') }}" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> {{ __('h_message.back') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('h_message.message_details') }}</h3>
                        </div>
                        <div class="card-body">
                            <strong><i class="fas fa-calendar-alt mr-1"></i> {{ __('h_message.sent_date') }}</strong>
                            <p class="text-muted">{{ $message->created_at->format('F d, Y') }} {{ __('h_message.at') }} {{ $message->created_at->format('H:i') }}</p>
                            <hr>

                            <strong><i class="fas fa-users mr-1"></i> {{ __('h_message.recipients') }} ({{ $message->recipient_count }})</strong>
                            <div class="mt-2">
                                @foreach($message->recipients() as $recipient)
                                    <span class="badge badge-info d-block mb-1">
                                        {{ $recipient->name }}
                                        @if(auth()->user()->is_role == 1)
                                            @if($message->isReadBy($recipient->id))
                                                <i class="fas fa-check-double text-success" title="{{ __('h_message.read') }}"></i>
                                            @else
                                                <i class="fas fa-check text-warning" title="{{ __('h_message.delivered') }}"></i>
                                            @endif
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                            <hr>

                            <strong><i class="fas fa-info-circle mr-1"></i> {{ __('h_message.status') }}</strong>
                            <p class="text-muted">
                                @if($message->is_urgent)
                                    <span class="badge badge-warning">{{ __('h_message.urgent') }}</span>
                                @else
                                    <span class="badge badge-secondary">{{ __('h_message.normal') }}</span>
                                @endif
                            </p>

                            @if(auth()->user()->is_role == 0 && $message->isReadBy(auth()->id()))
                                <hr>
                                <strong><i class="fas fa-eye mr-1"></i> {{ __('h_message.read_status') }}</strong>
                                <p class="text-muted">
                                    <span class="badge badge-success">{{ __('h_message.read') }}</span><br>
                                    <small>{{ $message->getReadTime(auth()->id())?->format('M d, Y H:i') }}</small>
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
