<div id="form-group--{{ $name }}" class="row form-group">
    @foreach($options_parent as $parent_id => $parent_label)
    <div id="checkbox-parent-{{ $parent_id }}" class="col-12 list_checkbox pb-4">
        <div class="row">
            <div class="col-12 parent-checkbox">
                <div class="icheck-cyan">
                    <input type="checkbox" value="{{ $parent_label }}" id="parent-{{ $parent_id }}" name="parent-{{ $name }}-{{ $parent_id }}" />
                    <label for="parent-{{ $parent_id }}" class="clause-name">{{ $parent_label }}</label>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($options_child as $child_id => $child_label)
                <div class="col-12 child-checkbox pl-3 pl-lg-5">
                    <div class="icheck-cyan">
                        <input type="checkbox" value="{{ $child_label }}" id="child-{{ $child_id }}" name="child-{{ $parent_id }}-{{ $child_id }}" />
                        <label for="child-{{ $child_id }}" class="clause-item-name">{{ $child_label }}</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
@push('scripts')
    <script>
        // -------------------------------------------------
        // text line-through for parent checkbox
        // -------------------------------------------------
        $('.list_checkbox').each(function(){
            var $this = $(this);
            var $parent_checkbox = $this.find('.parent-checkbox input[type=checkbox]');
            var $parent_label    = $this.find('.parent-checkbox label');
            var $child_checkbox  = $this.find('.child-checkbox input[type=checkbox]');
            var $child_label     = $this.find('.child-checkbox label');

            $parent_checkbox.on('click', function() {
                if ($parent_checkbox.is(':checked')) {
                    $parent_label.css('text-decoration','line-through');
                    $child_checkbox.prop('checked',true); // Overwrite by safety of icheck.
                    $child_label.css('text-decoration','line-through');
                } else {
                    $parent_label.css('text-decoration','');
                    $child_checkbox.prop('checked',false); // Overwrite by safety of icheck.
                    $child_label.css('text-decoration','');
                }
            });
        });
        // -------------------------------------------------

        // -------------------------------------------------
        // text line-through for child checkbox
        // -------------------------------------------------
        $('.list_checkbox .child-checkbox').each(function(){
            var $this = $(this);
            var $checbox = $this.find('input[type=checkbox]');
            var $label = $this.find('label');

            $checbox.on('click', function(){
                if($checbox.is(':checked')) {
                    $label.css('text-decoration','line-through');
                } else{
                    $label.css('text-decoration','');
                }
            });
        });
        // -------------------------------------------------

    </script>
@endpush