<div id="form-group--{{ $name }}" class="row form-group">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 col-header">
        @if( !empty($required) )
            <span class="bg-danger label-required">@lang('label.required')</span>
        @else
            <span class="bg-success label-required">@lang('label.optional')</span>
        @endif
        <strong class="field-title">{{ $label  }} </strong>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-10 col-content">
        <div class="row form-group">
            <div class="col-md-6">
                <select type="text" id="input-{{ $name }}" name="{{ $name }}" class="form-control {{$uniq_class}} @error($name) is-invalid @enderror" value="{{ !empty($value) ? $value : old($name) }}" {{ !empty($required) ? 'required' : '' }}>
                    @if(isset($options_type))
                        @if($options_type=="price")
                            @foreach($options as $id => $label)
                                <option value="{{ $label / 10000 }}" id="input-{{ $name }}-{{ $id }}" {{ number_format($value) == number_format($label) ? "selected" : "" }}>{{ number_format($label / 10000, 2) }} 万円</option>
                            @endforeach
                        @elseif($options_type=="land")
                            @foreach($options as $id => $label)
                                <option value="{{ $label / 3.30579 }}" id="input-{{ $name }}-{{ $id }}" {{ number_format($value) == number_format($label) ? "selected" : "" }}>{{ number_format($label / 3.30579, 2) }} 坪</option>
                            @endforeach
                        @else
                            @foreach($options as $id => $label)
                                <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>
                            @endforeach
                        @endif
                    @else
                        @foreach($options as $id => $label)
                            <option value="{{ $id }}" id="input-{{ $name }}-{{ $id }}" {{ $value == $id ? "selected" : "" }}>{{ $label }}</option>
                        @endforeach
                    @endif
                    
                </select>
            </div>
            <div class="col-md-6">
                <select type="text" id="input-{{ $name2 }}" name="{{ $name2 }}" class="form-control {{$uniq_class2}} @error($name2) is-invalid @enderror" value="{{ !empty($value2) ? $value : old($name2) }}" {{ !empty($required2) ? 'required' : '' }}>
                    @if(isset($options_type))
                        @if($options_type=="price")
                            @foreach($options2 as $id2 => $label2)
                                <option value="{{ $label2 / 10000 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ number_format($value2) == number_format($label2) ? "selected" : "" }}>{{ number_format($label2 / 10000, 2) }} 万円</option>
                            @endforeach
                        @elseif($options_type=="land")
                            @foreach($options2 as $id2 => $label2)
                                <option value="{{ $label2 / 3.30579 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{  number_format($value2) == number_format($label2)? "selected" : "" }}>{{ number_format($label2 / 3.30579, 2) }} 坪</option>
                            @endforeach
                        @else
                            @foreach($options2 as $id2 => $label2)
                                <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value2 == $id2 ? "selected" : "" }}>{{ $label2 }}</option>
                            @endforeach
                        @endif
                    @else
                        @foreach($options2 as $id2 => $label2)
                            <option value="{{ $id2 }}" id="input-{{ $name2 }}-{{ $id2 }}" {{ $value2 == $id2 ? "selected" : "" }}>{{ $label2 }}</option>
                        @endforeach
                    @endif
                    
                </select>
            </div>
        </div>
    </div>
</div>
