@extends("backend._base.app")

@section("content-wrapper")
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark h1title">{{$page_title}}</h1>
                </div>
                <div class="col-sm-6 text-sm">
                    @yield("breadcrumbs")
                </div>
            </div>
        </div>
    </div>
    @include("backend._includes.alert")
    <div class="container-fluid">
        <div class="row">
            @yield('content_before_table')
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-sm-6 card-title">
                                    <h3 class="card-title">@lang('label.list')</h3>
                                </div>
                                <div class="col-sm-6 card-header-link">
                                    @yield('top_buttons')
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info" style="width:100%">
                                        <thead>
                                        <tr>
                                            @yield('content')
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/moment/locale/ja.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <script>

        $(function() {
            // enable or disabled filtering server side
            var serverSide = true;
            @hasSection( 'extend-datatable' )
                @yield( 'extend-datatable' )
            @endif

            $("[data-col=action]").attr("rowspan", 2).addClass("text-center align-middle actionDatatables");
            var column = [];
            // COLUMN SEARCH
            $('#datatable thead tr').clone(true).appendTo('#datatable thead');

            // Apply common process to each header of colmun.
            $('#datatable thead tr:eq(1) th').each(function (i) {
                var id = $(this).data("col");
                var name = $(this).data('name');

                if (name != null) {
                    column.push({data: id, name: name})
                } else if( id !== "action" ){
                    column.push({data: id, name: id})
                }

                var title = $(this).text();
                var attr = $(this).attr('rowspan');
                var select = $(this).data('select');
                var datePicker = $(this).data('datepicker');
                if (typeof attr !== typeof undefined && attr !== false) {
                    $(this).remove();
                }
                var placeholder = "@lang('label.search') " + title;
                if( id === "id" ){
                    placeholder = '';
                }

                if(select != null){
                    var html = '<select class="form-control select-'+i+'" id="search_'+id+'">';

                    html += '<option value="">'+'-'+'</option>';

                    for(var key in select){
                        var value = select[key];
                        html += '<option value="'+key+'">'+value+'</option>'
                    }

                    html += '</select>';
                    $(this).html(html);

                    $('select', this).on('change', function(){
                        if(table.column(i).search() !== this.value){
                            table.column(i).search(this.value).draw();
                        }
                    });
                }else if(datePicker != null){
                    $(this).html('<input type="text" class="form-control input-datepicker" placeholder="' + placeholder + '" value="" />');
                    $('.input-datepicker', this).on('apply.daterangepicker', function(ev, picker) {
                        // Apply datetime-text to controle.
                        let picker_datetime_for_jquery = picker.startDate.format('YYYY/MM/DD HH:MM') + ' - ' + picker.endDate.format('YYYY/MM/DD HH:MM');
                        let picker_datetime_for_back = picker_datetime_for_jquery.replaceAll('/','-');

                        $(this).val(picker_datetime_for_jquery);
                        // If changed value, apply to filter of column.
                        if(table.column(i).search() !== picker_datetime_for_back){
                            // set search -> send the filter condition to backend.
                            table.column(i).order('desc').search(picker_datetime_for_back).draw();
                        }
                    });
                    $('.input-datepicker', this).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        if(table.column(i).search() !== this.value){
                            table.column(i).order('desc').search(this.value).draw();
                        }
                    });
                }else{
                    $(this).html('<input class="form-control input-'+i+'" type="text" placeholder="' + placeholder + '" />');

                    $('input', this).on('keyup change', function () {
                        if (table.column(i).search() !== this.value) {
                            table.column(i).search(this.value).draw();
                        }
                    });
                }
            });
            if( $("[data-col=action]").length ){
                column.push({data: 'action', name: 'action', orderable: false, searchable: false});
            }

            // DATATABLE SETUP
            let table = $('#datatable').DataTable({
                "order": [[0, "desc"]],
                "orderCellsTop": true,
                "fixedHeader": true,
                "paging": true,
                "lengthChange": true,
                "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "@lang('label.all')"]],
                "searching": true,
                "ordering": true,
                "info": true,
                "scrollX": true,
                "autoWidth": true,
                "processing": true,
                "responsive": true,
                "serverSide": serverSide,
                "stateSave": true,
                "dom": 'lrtip', // explain > https://datatables.net/reference/option/dom
                "ajax": "{{ url()->current() . "/json" }}",
                "columnDefs": [
                    {"width": "10px", "targets": 0},
                ],
                "columns": column,
                @if(App::isLocale('jp'))
                "language": {
                    "url": "{{asset('js/backend/adminlte/Japanese.json')}}"
                }
                @endif
            });
            // for debug.
            // _global_datatable = table;

            // REMEMBER LAST SEARCH
            let state = table.state.loaded();
            if(state){
                table.columns().eq(0).each(function(i){
                    let colSearch = state.columns[i].search;
                    if(colSearch.search){
                        $('.input-'+i).val(colSearch.search);
                        $('.select-'+i).val(colSearch.search);
                    }
                });

                table.draw();
            }


            // DELETE
            $('#datatable').on('click', '.deleteData[data-remote]', function (e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let url = $(this).data('remote');
                // CONFIRMATION
                if (confirm('@lang('label.jsConfirmDeleteData')')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {method: 'DELETE', submit: true}
                    }).always(function (data) {
                        console.log(data);
                        $('#datatable').DataTable().draw(false);
                        if (data === 1){
                          toastr.success('@lang('label.jsInfoDeletedData')');
                        }
                        else {
                          toastr.error('@lang('label.FAILED_DELETE_SELF_MESSAGE')');
                        }

                    });
                } else
                    toastr.error('@lang('label.jsSorry')');
            });

            $('.input-datepicker').daterangepicker({
                autoUpdateInput: false,
                singleDatePicker: false,
                showDropdowns: true,
                timePicker: true,
                locale: {
                    format: 'YYYY-M-D HH:MM',
                },
                ranges: {
                    '直近30日': [moment().subtract('days', 29), moment()],
                    '今月': [moment().startOf('month'), moment().endOf('month')],
                    '先月': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                opens: 'left',
                locale: {
                    applyLabel: '反映',
                    cancelLabel: '取消',
                    fromLabel: '開始日',
                    toLabel: '終了日',
                    weekLabel: 'W',
                    customRangeLabel: '自分で指定',
                    daysOfWeek: moment.weekdaysMin(), // Get JA strings by moment
                    monthNames: moment.monthsShort(), // Get JA strings by moment
                    firstDay: moment.localeData()._week.dow
                },
            });
        });
    </script>
@endpush
