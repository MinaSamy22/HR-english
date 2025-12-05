{{-- resources/views/backend/partials/attendance-summary.blade.php --}}
<!-- Preview Section -->
<div class="card-footer bg-light">
    <div class="row">
        <div class="col-md-8">
            <div class="info-box bg-gradient-info">
                <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                <div class="info-box-content">
                    <strong>{{ __('dashboard.summary') }}:</strong>

                    <span class="info-box-number">
                        {{ __('dashboard.late_arrival_threshold') }}: <span
                            id="late_deduction_preview">{{ $setting->late_threshold_minutes ?? 0 }}</span>
                        {{ __('dashboard.min') }}
                    </span>
                    <span class="info-box-number">
                        {{ __('dashboard.half_day_threshold') }}: <span
                            id="half_day_threshold_minutes">{{ $setting->half_day_threshold_minutes ?? 0 }}</span>
                        {{ __('dashboard.min') }}
                    </span>
                     <span class="info-box-number">
                        {{ __('dashboard.absent_threshold') }}: <span
                            id="half_day_threshold_minutes">{{ $setting->absent_threshold_minutes ?? 0 }}</span>
                        {{ __('dashboard.min') }}
                    </span>

                    <div class="progress">
                        <div class="progress-bar" id="late_progress" style="width: 100%">
                        </div>
                    </div>

                    <span class="info-box-number mt-1">
                        {{ __('dashboard.half_day') }}: <span
                            id="half_day_deduction_preview">{{ $setting->half_day_deduction_percentage ?? 0 }}</span>%
                        {{ __('dashboard.deduction') }}
                    </span>
                    <span class="info-box-number">
                        {{ __('dashboard.late_arrival') }}: <span
                            id="late_deduction_preview">{{ $setting->late_deduction_percentage ?? 0 }}</span>%
                        {{ __('dashboard.deduction') }}
                    </span>

                    <div class="progress">
                        <div class="progress-bar" id="late_progress" style="width: 100%">
                        </div>
                    </div>


                    <span class="info-box-number">
                        {{ __('dashboard.official_holidays') }}:
                        <ul id="official_holidays_preview" class="pl-3 mb-0">
                            @foreach (json_decode($setting->official_holidays ?? '[]') as $holiday)
                                <li>{{ $holiday->title }} -
                                    {{ \Carbon\Carbon::parse($holiday->date)->format('F j, Y') }}
                                </li>
                            @endforeach
                        </ul>
                    </span>
                    <div class="progress">
                        <div class="progress-bar" id="half_day_progress"
                            style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-right mt-4">
                <button type="button" class="btn btn-default mr-2"
                    onclick="location.reload()">
                    <i class="fas fa-undo"></i> {{ __('dashboard.refresh') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('dashboard.save_policy') }}
                </button>
            </div>
        </div>
    </div>
</div>
<!-- /Preview Section -->
