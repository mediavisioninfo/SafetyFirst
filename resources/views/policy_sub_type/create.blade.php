{{Form::open(array('url'=>'policy-sub-type','method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12">
            {{Form::label('title',__('Title'),array('class'=>'form-label'))}}
            {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter title')))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('type', __('Type'),['class'=>'form-label']) }}
            {!! Form::select('type', $types, null,array('class' => 'form-control hidesearch','required'=>'required')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}


