<div id="form-group--{{ $name }}" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }}</strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        @if ($page_type == "create")
            <div class="row form-group" style="border-bottom: none;">
                <div class="col-md-5">
                    <select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control {{$uniq_class}} @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>
                        @foreach($options as $id => $label)
                            <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control {{$uniq_class2}} @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>
                        @foreach($options2 as $id2 => $label2)
                            <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value2 == $id2 ? "selected" : "" }}>{{ $label2 }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                </div>
            </div>
        @else
            @foreach($foreach_edit as $fda)
                <div class="row form-group inputFormRow" style="border-bottom: none;">
                    <div class="col-md-5">
                        <select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control {{$uniq_class}} @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>
                            @foreach($options as $id => $label)
                                <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $fda->cities_id == $id ? "selected" : "" }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control {{$uniq_class2}} @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>
                            @foreach($options2 as $id2 => $label2)
                                <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $fda->towns_id == $id2 ? "selected" : "" }}>{{ $label2 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger removeRow1">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif

        <div id="newRow1"></div>

        <div class="row form-group" style="margin-top:0px;padding-top:0px;border-bottom: none;">
            <button type="button" class="btn btn-info" id="input-submit" onclick="add_new_row()">
                <i class="fas fa-plus"></i> @lang('label.add_user_fav_area')
            </button> 
        </div>

    </div>
</div>

@push('scripts')
<script>
        // add row
        function add_new_row() {
            var html = '';
            html += '<div class="row form-group inputFormRow" style="margin-top:0px;padding-top:0px;border-bottom: none;">' +
                '<div class="col-md-5">' +
                    '<select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control {{$uniq_class}} @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>' +
                        '@foreach($options as $id => $label)' +
                            '<option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-5">' +
                    '<select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control {{$uniq_class2}} @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>' +
                        '@foreach($options2 as $id2 => $label2)' +
                            '<option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value == $id2 ? "selected" : "" }}>{{ $label2 }}</option>' +
                        '@endforeach' +
                    '</select>' +
                '</div>' +
                '<div class="col-md-2">' +
                    '<button type="button" class="btn btn-danger removeRow1" >' +
                        '<i class="fas fa-minus"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';

            $('#newRow1').append(html);
        };

        // remove row
        $(document).on('click', '.removeRow1', function () {
            $(this).closest('.inputFormRow').remove();
        });


    </script>

@endpush