{{Form::open(array('route'=>array('claim.document.store',$claimId),'method'=>'post', 'enctype' => "multipart/form-data"))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('document_type', __('Document Type'), array('class' => 'form-label')) }}
            {!! Form::select('document_type', $documentType, old('document_type'), array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
        </div>
        <div class="form-group  col-md-12">
            {{Form::label('document',__('Document'),array('class'=>'form-label'))}}
            {{Form::file('document',array('class'=>'form-control', 'required' => 'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), array('class' => 'form-label')) }}
            {!! Form::select('status', $status, old('status'), array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}


