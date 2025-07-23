{{Form::open(array('route'=>array('insurance.nominee.store',$insuranceId),'method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('name',__('Name'),array('class'=>'form-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter name'), 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('dob',__('Date of Birth'),array('class'=>'form-label'))}}
            {{Form::date('dob',null,array('class'=>'form-control', 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('percentage',__('Percentage'),array('class'=>'form-label'))}}
            {{Form::number('percentage',null,array('class'=>'form-control','placeholder'=>__('Enter percentage'), 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('relation',__('Relation'),array('class'=>'form-label'))}}
            {{Form::text('relation',null,array('class'=>'form-control','placeholder'=>__('Enter relation'), 'required' => 'required'))}}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}


