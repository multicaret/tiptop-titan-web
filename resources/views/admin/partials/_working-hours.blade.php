<div class="row">
    <div class="col-12 mb-4">
        <business-hours
            :days="workingHours"
            name="days"
            type="select"
            :time-increment="15"
            color="#FEC63D"
            :hour-format24="true"
        ></business-hours>
        <input type="hidden" name="business_hours" :value="JSON.stringify(workingHours)" />
    </div>
</div>
