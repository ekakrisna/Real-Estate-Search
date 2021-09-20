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
                <div class="col-md-3">
                            <strong class="field-title">{{ $label1  }}</strong>
                            <select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>
                                @foreach($options as $id => $tittle)
                                    <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $tittle }}</option>
                                @endforeach
                            </select>
                </div>
                <div class="col-md-3">
                            <strong class="field-title">{{ $label2  }}</strong>
                            <select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>
                                @foreach($options2 as $id2 => $tittle2)
                                    <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value2 == $id2 ? "selected" : "" }}>{{ $tittle2 }}</option>
                                @endforeach
                            </select>
                </div>
                <div class="col-md-3">

                            <strong class="field-title">{{ $label3  }}</strong>                        
                        <select type="text" id="input-{{ $name3 }}" name="{{ $name3 }}" class="form-control @error($name3) is-invalid @enderror" value="{{ !empty($value3) ? $value : old($name3) }}" {{ !empty($required3) ? 'required' : '' }}>
                            @foreach($options3 as $id3 => $tittle3)
                                <option value="{{ $id3 }}" id="input-{{ $name3 }}-{{ $id3 }}" {{ $value3 == $id3 ? "selected" : "" }}>{{ $tittle3 }}</option>
                            @endforeach
                        </select>                    
                </div>
                <div class="col-md-2">
                </div>
            </div>
        @else
            @foreach($foreach_edit as $fda)
                <div class="row form-group inputFormRow" style="border-bottom: none;">
                    <div class="col-md-3">
                                <strong class="field-title">{{ $label1  }}</strong>                            
                            <select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>
                                @foreach($options as $id => $tittle)
                                    <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $fda->cities_id == $id ? "selected" : "" }}>{{ $tittle }}</option>
                                @endforeach
                            </select>                        
                    </div>
                    <div class="col-md-3">                                                    
                            <strong class="field-title">{{ $label2  }}</strong>                            
                            <select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>
                                @foreach($options2 as $id2 => $tittle2)
                                    <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $fda->towns_id == $id2 ? "selected" : "" }}>{{ $tittle2 }}</option>
                                @endforeach
                            </select>                        
                    </div>
                    <div class="col-md-3">                        
                            <strong class="field-title">{{ $label3  }}</strong>                            
                            <select type="text" id="input-{{ $name3 }}" name="{{ $name3 }}" class="form-control @error($name3) is-invalid @enderror" value="{{ !empty($value3) ? $value : old($name3) }}" {{ !empty($required3) ? 'required' : '' }}>
                                @foreach($options3 as $id3 => $tittle3)
                                    <option value="{{ $id3 }}" id="input-{{ $name3 }}-{{ $id3 }}" {{ $value3 == $id3 ? "selected" : "" }}>{{ $tittle3 }}</option>
                                @endforeach
                            </select>                        
                    </div>
                    <div class="col-md-2">
                        <strong class="field-title"></strong>                            
                        <button type="button" class="btn btn-danger removeRow1">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @endif

        <div id="newRow1"></div>        
            <button type="button" class="btn btn-info" id="input-submit" onclick="add_new_row()">
                <i class="fas fa-plus"></i> @lang('label.add_deliver_conditions')
            </button>         

    </div>
</div>

@push('scripts')
<script>
        // add row
        function add_new_row() {
            var html = '';
            html += '<div class="row form-group inputFormRow" style="margin-top:0px;padding-top:0px;border-bottom: none;">' +
                '<div class="col-md-3">' +
                            '<strong class="field-title">{{ $label1  }}</strong>'+                        
                        '<select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>' +
                            '@foreach($options as $id => $tittle)' +
                                '<option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $tittle }}</option>' +
                            '@endforeach' +
                        '</select>' +                    
                '</div>'+
                '<div class="col-md-3">' +                    
                            '<strong class="field-title">{{ $label2  }}</strong>'+                        
                    '<select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>' +
                        '@foreach($options2 as $id2 => $tittle2)' +
                            '<option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value == $id2 ? "selected" : "" }}>{{ $tittle2 }}</option>' +
                        '@endforeach' +
                    '</select>' +                    
                '</div>' +
                '<div class="col-md-4">'+                    
                    '<strong class="field-title">{{ $label3  }}</strong>'+                        
                    '<div class="row">' +
                        '<div class="col-md-9">'+                    
                            '<select type="text" id="input-{{ $name3 }}" name="{{ $name3 }}" class="form-control @error($name3) is-invalid @enderror" value="{{ !empty($value3) ? $value : old($name3) }}" {{ !empty($required3) ? 'required' : '' }}>'+
                                '@foreach($options3 as $id3 => $tittle3)'+
                                    '<option value="{{ $id3 }}" id="input-{{ $name3 }}-{{ $id3 }}" {{ $value3 == $id3 ? "selected" : "" }}>{{ $tittle3 }}</option>'+
                                '@endforeach'+
                            '</select>'+                    
                        '</div>'+
                        '<div class="col-md-auto">'+                    
                            '<button type="button" class="btn btn-danger removeRow1" >' +
                                '<i class="fas fa-minus"></i>' +
                            '</button>' +
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-md-2">' +                    
                    
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