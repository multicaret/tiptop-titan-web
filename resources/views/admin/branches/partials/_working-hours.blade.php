@if($auth->is_doctor)
    @include('admin.calendar.partials._doctor-top-bar')
@endif
<table class="table">
    <thead>
    <tr>
        <th>Day</th>
        <th>Is the day off</th>
        <th>Open at</th>
        <th>closes_at</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="(day , selectedDoctorIndex) in workingHours">
        <td>
            <b-row>
                <pre>
                    @{{  day, selectedDoctorIndex}}
                </pre>
                <b-col class="justify-content-center">
                    @{{ daysTranslation['working_day_'+ (day.day )] }}
                </b-col>
            </b-row>
        </td>
        <td>
            <div>
                <b-form-checkbox
                    :id="'is_day_off_' + selectedDoctorIndex"
                    v-model="day.is_day_off">
                </b-form-checkbox>
            </div>
        </td>
        <td>
            <div class="">
                <b-row>
                    <b-col>
                        <b-form-timepicker class="d-flex h-auto" :disabled="!day.is_day_off ? false : true"
                                           v-model="day.opens_at" minutes-step="15"></b-form-timepicker>
                    </b-col>
                </b-row>
            </div>
        </td>
        <td>
            <div>
                <b-row>
                    <b-col>
                        <b-form-timepicker class="d-flex h-auto" :disabled="!day.is_day_off ? false : true"
                                           v-model="day.closes_at" minutes-step="15"></b-form-timepicker>
                    </b-col>
                </b-row>
            </div>
        </td>
    </tr>
    </tbody>
</table>
