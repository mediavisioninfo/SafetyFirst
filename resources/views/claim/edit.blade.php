{{Form::model($claim, array('route' => array('claim.update', $claim->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('claim_id',__('Claim Number'),array('class'=>'form-label'))}}
            <div class="input-group">
                {{Form::text('claim_id',$claim->claim_id,array('class'=>'form-control','placeholder'=>__('Enter claim number')))}}
            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('policy_number',__('Policy Number'),array('class'=>'form-label'))}}
            <div class="input-group">
                {{Form::text('policy_number',$claim->policy_number,array('class'=>'form-control','placeholder'=>__('Enter Policy Number')))}}
            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('date',__('Date'),array('class'=>'form-label'))}}
            {{Form::date('date',$claim->date,array('class'=>'form-control'))}}
        </div>

        <!-- Loss Date Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('loss_date',__('Loss Date'),array('class'=>'form-label'))}}
            {{Form::date('loss_date',$claim->loss_date,array('class'=>'form-control'))}}
        </div>

        <!-- Location Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('location',__('Location'),array('class'=>'form-label'))}}
            {{Form::text('location',$claim->location,array('class'=>'form-control','placeholder'=>__('Enter location')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('place_of_survey',__('Place of Survey'),array('class'=>'form-label'))}}
            {{Form::text('place_of_survey',$claim->place_of_survey,array('class'=>'form-control','placeholder'=>__('Enter Place of Survey')))}}
        </div>

        <!-- E-Mail Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('email',__('E-Mail'),array('class'=>'form-label'))}}
            {{Form::email('email',$claim->email,array('class'=>'form-control','placeholder'=>__('Enter email address')))}}
        </div>
        
        <!-- Mobile Number Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('mobile',__('Mobile Number'),array('class'=>'form-label'))}}
            {{Form::text('mobile',$claim->mobile,array('class'=>'form-control','placeholder'=>__('Enter mobile number')))}}
        </div>

        <!-- Workshop Name Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_name',__('Workshop Name'),array('class'=>'form-label'))}}
            {{Form::text('workshop_name',$claim->workshop_name,array('class'=>'form-control','placeholder'=>__('Enter Workshop Name')))}}
        </div>
        <!-- Workshop E-Mail Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_email',__('Workshop E-Mail'),array('class'=>'form-label'))}}
            {{Form::email('workshop_email',$claim->workshop_email,array('class'=>'form-control','placeholder'=>__('Enter Workshop E-Mail')))}}
        </div>

        <!-- Workshop Address Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_address',__('Workshop Address'),array('class'=>'form-label'))}}
            {{Form::text('workshop_address',$claim->workshop_address,array('class'=>'form-control','placeholder'=>__('Enter Workshop Address')))}}
        </div>

        <!-- Workshop Mobile Number Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('workshop_mobile_number',__('Workshop Mobile Number'),array('class'=>'form-label'))}}
            {{Form::number('workshop_mobile_number',$claim->workshop_mobile_number,array('class'=>'form-control','placeholder'=>__('Enter Workshop Mobile Number')))}}
        </div>
        
        <!-- Claim Amount Field -->
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('claim_amount',__('Claim Amount'),array('class'=>'form-label'))}}
            {{Form::number('claim_amount',$claim->claim_amount,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter claim amount')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
            {!! Form::select('status', $status, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('state_id', __('State'),['class'=>'form-label']) }}
            {!! Form::select('state_id', $states, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('city_id', __('City'),['class'=>'form-label']) }}
            {!! Form::select('city_id', $cities, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>
        
        <div class="form-group col-md-6 col-lg-6">
            {{Form::label('ensurance_email',__('Ensurance Email'),array('class'=>'form-label'))}}
            {{Form::text('ensurance_email',$claim->ensurance_email,array('class'=>'form-control','placeholder'=>__('Enter Ensurance Email')))}}
        </div>

        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('insurance_company_id', __('Insurance Company'),['class'=>'form-label']) }}
            {!! Form::select('insurance_company_id', $insurance_companies, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}

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
                var edit_insurance= $('#edit_insurance').val();
                $('.insurance').empty();
                var insurance = `<select class="form-control hidesearch insurance" id="insurance" name="insurance"></select>`;
                $('.insurance_div').html(insurance);
                $.each(data, function (key, value) {
                    if(key==edit_insurance){
                        $('.insurance').append('<option selected value="' + key + '">' + value +'</option>');
                    }else{
                        $('.insurance').append('<option value="' + key + '">' + value + '</option>');
                    }
                });
                $('.hidesearch').select2({
                    minimumResultsForSearch: -1
                });
            },
        });
    });
    $('#customer').trigger('change');
</script>
