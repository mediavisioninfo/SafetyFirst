{{Form::model($policyFor, array('route' => array('policy-for.update', $policyFor->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            {{Form::label('buying_for',__('Policy Buy For'),array('class'=>'form-label'))}}
            {{Form::text('buying_for',null,array('class'=>'form-control','placeholder'=>__('Enter policy buy for')))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('policy_type', __('Policy Type'),['class'=>'form-label']) }}
            {!! Form::select('policy_type', $types, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}

