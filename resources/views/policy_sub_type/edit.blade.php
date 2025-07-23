{{Form::model($policySubType, array('route' => array('policy-sub-type.update', $policySubType->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            {{Form::label('title',__('Title'),array('class'=>'form-label'))}}
            {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter note title')))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'),['class'=>'form-label']) }}
            {!! Form::select('type', $types, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Update'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}

