{{Form::open(array('url'=>'claim','method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('claim_id', __('Claim Number'), ['class' => 'form-label']) }}
            <div class="input-group">
                {{ Form::text('claim_id', null, [
                    'class' => 'form-control',
                    'placeholder' => __('Enter claim number'),
                    'maxlength' => 20,
                    'minlength' => 20, 
                    'onchange' => 'removeSpecialChars(this, "claim_id_error")'
                ]) }}
            </div>
            <small id="claim_id_error" class="text-danger"></small>
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('policy_number', __('Policy Number'), ['class' => 'form-label']) }}
            {{ Form::text('policy_number', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter policy number'),
                'maxlength' => 20,
                'minlength' => 20,
                'onchange' => 'removeSpecialChars(this, "policy_number_error")'
            ]) }}
            <small id="policy_number_error" class="text-danger"></small>
        </div>

        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('date',__('Intimation Date'),array('class'=>'form-label'))}}
            {{Form::date('date',date('Y-m-d'),array('class'=>'form-control'))}}
        </div>

        <!-- This code is wrritten By Durgaraj -->
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('loss_date',__('Loss Date'),array('class'=>'form-label'))}}
            {{Form::date('loss_date',null,array('class'=>'form-control','placeholder'=>__('Enter loss date')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('location',__('Location'),array('class'=>'form-label'))}}
            {{Form::text('location',null,array('class'=>'form-control','placeholder'=>__('Enter location')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('place_of_survey',__('Place of Survey'),array('class'=>'form-label'))}}
            {{Form::text('place_of_survey',null,array('class'=>'form-control','placeholder'=>__('Enter Place of Survey')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('email',__('E-Mail'),array('class'=>'form-label'))}}
            {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter email')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('mobile',__('Mobile Number'),array('class'=>'form-label'))}}
            {{ Form::number('mobile', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter mobile number'),
                'min' => '1000000000', // minimum 10-digit number
                'max' => '999999999999', // max 12-digit number
                'oninput' => "if(this.value.length > 12) this.value = this.value.slice(0, 12)"
            ]) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_name',__('Workshop Name'),array('class'=>'form-label'))}}
            {{Form::text('workshop_name',null,array('class'=>'form-control','placeholder'=>__('Enter Workshop Name')))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_email',__('Workshop E-Mail'),array('class'=>'form-label'))}}
            {{Form::email('workshop_email',null,array('class'=>'form-control','placeholder'=>__('Enter Workshop Email')))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_address',__('Workshop Address'),array('class'=>'form-label'))}}
            {{Form::text('workshop_address',null,array('class'=>'form-control','placeholder'=>__('Enter workshop Address number')))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('workshop_mobile_number', __('Workshop Mobile Number'), ['class' => 'form-label']) }}
            {{ Form::number('workshop_mobile_number', null, [
                'class' => 'form-control',
                'placeholder' => __('Enter workshop mobile number'),
                'min' => '1000000000', // minimum 10-digit number
                'max' => '999999999999', // max 12-digit number
                'oninput' => "if(this.value.length > 12) this.value = this.value.slice(0, 12)"
            ]) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('claim_amount',__('Claim Amount'),array('class'=>'form-label'))}}
            {{Form::number('claim_amount',null,array('class'=>'form-control','placeholder'=>__('Enter claim amount')))}}
        </div>
        
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
            {!! Form::select('status', $status, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('state_id', 'State', ['class' => 'form-label']) }}
            {!! Form::select('state_id', $states, null, [
                'class' => 'form-control', 
                'placeholder' => '-- Select State --', 
                'id' => 'state_id'
            ]) !!}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('city_id', 'City', ['class' => 'form-label']) }}
            {!! Form::select('city_id', [], null, [
                'class' => 'form-control', 
                'placeholder' => '-- Select City --', 
                'id' => 'city_id'
            ]) !!}
        </div>
        
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('ensurance_email',__('Ensurance Email'),array('class'=>'form-label'))}}
            {{Form::email('ensurance_email',null,array('class'=>'form-control','placeholder'=>__('Enter Ensurance Email')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('insurance_company_id', 'Insurance Company', ['class' => 'form-label']) }}
            {!! Form::select('insurance_company_id', $insurance_companies, null, [
                'class' => 'form-control',
                'placeholder' => '-- Select Insurance Company --'
            ]) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   function removeSpecialChars(input, errorElementId) {
        const original = input.value;
        const cleaned = original.replace(/[^a-zA-Z0-9]/g, '');
        const errorElement = document.getElementById(errorElementId);

        let errorMessage = '';

        if (original !== cleaned) {
            input.value = cleaned;
            errorMessage = 'Special characters and spaces are not allowed.';
        } else if (cleaned.length < 20) {
            errorMessage = 'Minimum 20 characters are required.';
        }

        errorElement.innerText = errorMessage;
    }
</script>
<script>
$(document).ready(function () {
    $('#state_id').on('change', function () {
        let stateId = $(this).val();
        $('#city_id').html('<option>Loading...</option>');

        if (stateId) {
            $.ajax({
                url: `/get-cities/${stateId}`,
                type: 'GET',
                success: function (data) {
                    let cityOptions = '<option value="">-- Select City --</option>';
                    $.each(data, function (id, name) {
                        cityOptions += `<option value="${id}">${name}</option>`;
                    });
                    $('#city_id').html(cityOptions);
                }
            });
        } else {
            $('#city_id').html('<option value="">-- Select City --</option>');
        }
    });
});
</script>

<script>

    $('#customer').on('change', function () {
        "use strict";
        var customer = $(this).val();
        var url = '{{ route("customer.insurance") }}';
        $.ajax({
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                customer: customer,
            },
            type: 'POST',
            success: function (data) {

                $('.insurance').empty();
                var insurance = `<select class="form-control hidesearch insurance" id="insurance" name="insurance"></select>`;
                $('.insurance_div').html(insurance);
                $.each(data, function (key, value) {
                    $('.insurance').append('<option value="' + key + '">' + value + '</option>');
                });
                $('.hidesearch').select2({
                    minimumResultsForSearch: -1
                });
            },

        });
    });

</script>
