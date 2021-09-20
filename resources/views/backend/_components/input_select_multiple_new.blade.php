<div id="form-group--{{ $name[0] }}" class="row form-group {{$hide_class}}">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        @if ($page_type == "create")
            <div class="row form-group" style="border-bottom: none;">
                @foreach($name as $names)
                <div class="col-md-3">
                    <div class="row form-group" style="border-bottom: none;">
                        <div class="col-md-4">
                            {{$labels[$loop->index]}}:
                        </div>
                        <div class="col-md-8">
                            <select type="text" id="input-{{ $names }}" name="{{ $names }}" class="form-control {{$uniq_class[$loop->index]}} @error($names) is-invalid @enderror" value="{{ !empty($value[$loop->index]) ? $value : old($names) }}" {{ !empty($required) ? 'required' : '' }}>
                                @php $values = $value[$loop->index]; @endphp
                                @foreach($options[$loop->index] as $id => $label)
                                    <option value="{{ $id }}" id="input-{{ $names }}-{{ $id }}" {{ $values == $id ? "selected" : "" }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="col-md-2">
                </div>
            </div>
        @endif

        <div id="{{$add_id}}"></div>

        <div class="row form-group" style="margin-top:0px;padding-top:0px;border-bottom: none;">
            <button type="button" class="btn btn-info" id="input-submit" onclick="add_new_row('{{$add_id}}')">
                <i class="fas fa-plus"></i> {{$button_add_label}}
            </button> 
        </div>

    </div>
</div>

@push('scripts')
<script>
        // add row
        function add_new_row(add_id) {
            var html = '';
            html += '<div class="row form-group inputFormRow" style="margin-top:0px;padding-top:0px;border-bottom: none;">' +
                '@foreach($name as $names)' +
                '<div class="col-md-3">' +
                    '<div class="row form-group" style="border-bottom: none;">' +
                        '<div class="col-md-4">' +
                            '{{$labels[$loop->index]}}:' +
                        '</div>' +
                        '<div class="col-md-8">' +
                            '<select type="text" id="input-{{ $names }}" name="{{ $names }}" class="form-control {{$uniq_class[$loop->index]}} @error($names) is-invalid @enderror" value="{{ !empty($value[$loop->index]) ? $value : old($names) }}" {{ !empty($required) ? 'required' : '' }}>' +
                                '@foreach($options[$loop->index] as $id => $label)' +
                                    '<option value="{{ $id }}" id="input-{{ $names }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>' +
                                '@endforeach' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '@endforeach' +
                '<div class="col-md-2">' +
                    '<button type="button" class="btn btn-danger removeRow1" >' +
                        '<i class="fas fa-minus"></i>' +
                    '</button>' +
                '</div>' +
            '</div>';

            $('#'+add_id).append(html);
        };

        // remove row
        $(document).on('click', '.removeRow1', function () {
            $(this).closest('.inputFormRow').remove();
        });


</script>

@endpush