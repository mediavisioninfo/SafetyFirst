{{Form::model($policyDuration, array('route' => array('policy-duration.update', $policyDuration->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            {{Form::label('duration_terms',__('Duration Terms'),array('class'=>'form-label'))}}
            {{Form::text('duration_terms',null,array('class'=>'form-control','placeholder'=>__('Enter duration terms')))}}
        </div>
        <div class="form-group  col-md-12">
            {{Form::label('duration_month',__('Duration Month'),array('class'=>'form-label'))}}
            {{Form::number('duration_month',null,array('class'=>'form-control','placeholder'=>__('Enter duration month')))}}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}

