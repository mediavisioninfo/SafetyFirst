
@extends('layouts.app')
@section('page-title')
    {{__('Policy Edit')}}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{route('dashboard')}}"><h1>{{__('Dashboard')}}</h1></a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{route('policy.index')}}">{{__('Policy')}}</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{__('Edit')}}</a>
        </li>
    </ul>
@endsection

@section('content')
    {{Form::model($policy, array('route' => array('policy.update', $policy->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-xl-8 col-md-7 cdx-xl-60">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Policy Information')}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('title', __('Title'), array('class' => 'form-label')) }}
                            {{ Form::text('title', null, array('class' => 'form-control','placeholder'=>__('Enter policy title'), 'required' => 'required')) }}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('policy_type', __('Policy Type'), array('class' => 'form-label')) }}
                            {!! Form::select('policy_type', $policyType, null, array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            <input type="hidden" id="edit_policy_subtype" value="{{$policy->policy_subtype}}">
                            {{Form::label('policy_subtype',__('Policy Sub Type'),array('class'=>'form-label'))}}
                            <div class="policy_subtype_div">
                                <select class="form-control hidesearch policy_subtype" id="policy_subtype"
                                        name="policy_subtype">
                                    <option value="">{{__('Select Sub TYpe')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-lg-3">
                            {{ Form::label('coverage_type', __('Coverage Type'), array('class' => 'form-label')) }}
                            {!! Form::select('coverage_type', $coverageType, null, array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
                        </div>
                        <div class="form-group col-md-3 col-lg-3">
                            {{ Form::label('total_insured_person', __('Total Insured Person'), array('class' => 'form-label')) }}
                            {{ Form::number('total_insured_person', null, array('class' => 'form-control','placeholder'=>__('Enter total insured person'), 'required' => 'required')) }}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('liability_risk', __('Liability Risk'), array('class' => 'form-label')) }}
                            {!! Form::select('liability_risk', $liabilityRisk, null, array('class' => 'form-control  basic-select hidesearch', 'required' => 'required')) !!}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('sum_assured', __('Sum Assured'), array('class' => 'form-label')) }}
                            {{ Form::number('sum_assured', null, array('class' => 'form-control','placeholder'=>__('Enter sum assured'), 'required' => 'required')) }}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('policy_required_document', __('Policy Required Document'), array('class' => 'form-label')) }}
                            {!! Form::select('policy_required_document[]', $documentType, explode(',',$policy->policy_required_document), array('class' => 'form-control  basic-select hidesearch','multiple', 'required' => 'required')) !!}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('claim_required_document', __('Claim Required Document'), array('class' => 'form-label')) }}
                            {!! Form::select('claim_required_document[]', $documentType,explode(',',$policy->claim_required_document), array('class' => 'form-control  basic-select hidesearch','multiple', 'required' => 'required')) !!}
                        </div>
                        <div class="form-group col-md-6 col-lg-6">
                            {{ Form::label('tax', __('Tax'), array('class' => 'form-label')) }}
                            {!! Form::select('tax[]', $taxes, !empty($policy->tax)?explode(',',$policy->tax):null, array('class' => 'form-control  basic-select hidesearch','multiple')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-5 cdx-xl-40">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Policy Pricing')}}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{__('Terms Duration')}}</th>
                                <th>{{__('Month')}}</th>
                                <th>{{__('Price')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($policy->pricing as $duration)
                                <tr>
                                    <td><input type="text" class="form-control" name="duration_terms[]"
                                               value="{{$duration->duration_terms}}"></td>
                                    <td><input type="number" class="form-control" name="duration_month[]" value="{{$duration->duration_month}}"></td>
                                    <td><input type="number" class="form-control" name="price[]" value="{{$duration->price}}"></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Policy Description')}}</h4>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="description" name="description" placeholder="{{__('Enter policy description')}}">{!! $policy->description !!}</textarea>
                </div>
            </div>
        </div>
        <div class="form-group col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4>{{__('Policy Terms & Condition')}}</h4>
                </div>
                <div class="card-body">
                    <textarea class="form-control" id="terms_conditions" name="terms_conditions" placeholder="{{__('Enter terms & conditions')}}">{!! $policy->terms_conditions !!}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="form-group text-end">
            {{ Form::submit(__('Update'), array('class' => 'btn btn-primary ml-10')) }}
        </div>
    </div>
    {{ Form::close() }}

@endsection

@push('script-page')
    <script>

        $('#policy_type').on('change', function () {
            var policy_type = $(this).val();
            var url = '{{ route("policy.subtype", ":id") }}';
            url = url.replace(':id', policy_type);
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    policy_type: policy_type,
                },
                contentType: false,
                processData: false,
                type: 'GET',
                success: function (data) {
                    $('.policy_subtype').empty();
                    var policy_subtype = `<select class="form-control hidesearch policy_subtype" id="policy_subtype" name="policy_subtype"></select>`;
                    $('.policy_subtype_div').html(policy_subtype);

                    var edit_policy_subtype= $('#edit_policy_subtype').val();
                    console.log(edit_policy_subtype)
                    $.each(data, function (key, value) {
                        if(key==edit_policy_subtype){
                            $('.policy_subtype').append('<option selected value="' + key + '">' + value +'</option>');
                        }else{
                            $('.policy_subtype').append('<option value="' + key + '">' + value + '</option>');
                        }
                    });
                    $('.hidesearch').select2({
                        minimumResultsForSearch: -1
                    });
                },

            });
        });
        $('#policy_type').trigger('change');
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', '|',
                    'link', 'unlink', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'indent', 'outdent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo', '|',
                    'alignment', 'fontBackgroundColor', 'fontColor', 'fontFamily', 'fontSize', 'highlight', '|',
                    'horizontalLine', 'pageBreak', '|',
                    'removeFormat', 'specialCharacters', 'findAndReplace'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', 'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .catch(error => {
                console.log(error);
            });
        ClassicEditor
            .create(document.querySelector('#terms_conditions'), {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', '|',
                    'link', 'unlink', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'indent', 'outdent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo', '|',
                    'alignment', 'fontBackgroundColor', 'fontColor', 'fontFamily', 'fontSize', 'highlight', '|',
                    'horizontalLine', 'pageBreak', '|',
                    'removeFormat', 'specialCharacters', 'findAndReplace'
                ],
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                image: {
                    toolbar: [
                        'imageTextAlternative', 'imageStyle:full', 'imageStyle:side', 'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn', 'tableRow', 'mergeTableCells'
                    ]
                },
                mediaEmbed: {
                    previewsInData: true
                }
            })
            .catch(error => {
                console.log(error);
            });
    </script>
@endpush
