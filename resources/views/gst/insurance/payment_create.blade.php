{{Form::open(array('route'=>array('insurance.payment.store',$insuranceId),'method'=>'post'))}}
<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-12 col-lg-12">
            {{Form::label('payment_date',__('Payment Date'),array('class'=>'form-label'))}}
            {{Form::date('payment_date',null,array('class'=>'form-control', 'required' => 'required'))}}
        </div>
        <div class="form-group  col-md-12 col-lg-12">
            {{Form::label('amount',__('Amount'),array('class'=>'form-label'))}}
            {{Form::number('amount',$amount,array('class'=>'form-control','placeholder'=>__('Enter amount'), 'required' => 'required'))}}
        </div>
    </div>
</div>
<div class="modal-footer">
    {{Form::submit(__('Create'),array('class'=>'btn btn-primary btn-rounded'))}}
</div>
{{ Form::close() }}


