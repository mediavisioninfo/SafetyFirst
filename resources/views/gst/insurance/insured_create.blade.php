{{Form::open(array('route'=>array('insurance.insured.store',$insuranceId),'method'=>'post'))}}
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
            {{Form::label('age',__('Age'),array('class'=>'form-label'))}}
            {{Form::number('age',null,array('class'=>'form-control','placeholder'=>__('Enter age'), 'required' => 'required'))}}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('gender', __('Gender'), array('class' => 'form-label')) }}
            {!! Form::select('gender', $gender, old('gender'), array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('blood_group',__('Blood Group'),array('class'=>'form-label'))}}
            {{Form::text('blood_group',null,array('class'=>'form-control','placeholder'=>__('Enter blood group'), 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('height',__('Height'),array('class'=>'form-label'))}}
            {{Form::number('height',null,array('class'=>'form-control','placeholder'=>__('Enter height'), 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-6 col-lg-6">
            {{Form::label('weight',__('Weight'),array('class'=>'form-label'))}}
            {{Form::number('weight',null,array('class'=>'form-control','placeholder'=>__('Enter weight'), 'required' => 'required'))}}
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


