@extends('system::template/master')
@section('title', $title)

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">List Pabrik</h3>
        @if (RoleAccess::whereMenu(29) == 1)
        <a href="{{ url('factory/create') }}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Tambah</a>
        @endif
    </div>
    
    <!-- /.card-header -->
    <div class="card-body" >
        <label class="float-right">
            <input type="text" name="serach" id="serach" class="form-control form-control-sm" placeholder="Search .." aria-controls="example1">
        </label>
        
        <table id="table_data" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th width="10%" class="sorting_asc" data-sorting_type="asc" data-column_name="factory_code" style="cursor: pointer">Kode<span id="factory_code_icon"></span></th>
                    <th class="sorting_asc" data-sorting_type="asc" data-column_name="factory_name" style="cursor: pointer">Factory<span id="factory_name_icon"></span></th>
                    <th class="sorting_asc" data-sorting_type="asc" data-column_name="factory_company_name" style="cursor: pointer">Nama Perusahaan<span id="factory_company_name_icon"></span></th>
                    <th class="sorting_asc" data-sorting_type="asc" data-column_name="factory_company_address" style="cursor: pointer">Alamat<span id="factory_company_address_icon"></span></th>
                    <th class="sorting_asc" data-sorting_type="asc" data-column_name="factory_email" style="cursor: pointer">Email<span id="factory_email_icon"></span></th>
                    @if (RoleAccess::whereMenu(29) > 0 && RoleAccess::whereMenu(29) < 3)
                    <th >Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @include('system::settings/factory/pagination_data')
            </tbody>
        </table>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="factory_id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />        
    </div>
    <!-- /.card-body -->

    <div id="block" style="display:none;" class="overlay dark"><i class="fas fa-2x fa-spin fa-sync-alt"></i></div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function(){

    function clear_icon(){
        $('#id_icon').html('');
        $('#post_title_icon').html('');
    }

    function fetch_data(page, sort_type, sort_by, query){
        $.ajax({
            url:"{{ url('factory') }}/fetch_data?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query,
            // beforeSend:function(){
            //     $('#block').removeAttr('style');
            // },
            success:function(data){
                // $('#block').attr('style', 'display:none');
                $('tbody').html('');
                $('tbody').html(data);
            }
        })
    }

    $(document).on('keyup', '#serach', function(){
        var query = $('#serach').val();
        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();
        var page = $('#hidden_page').val();
        fetch_data(page, sort_type, column_name, query);
    });

    $(document).on('click', '.sorting_asc', function(){
        var column_name = $(this).data('column_name');
        var order_type = $(this).data('sorting_type');
        var reverse_order = '';
        if(order_type == 'asc'){
            $(this).data('sorting_type', 'desc');
            reverse_order = 'desc';
            clear_icon();
            $('#'+column_name+'_icon').html('<i class="fas fa-angle-up"></i>');
        }

        if(order_type == 'desc'){
            $(this).data('sorting_type', 'asc');
            reverse_order = 'asc';
            clear_icon
            $('#'+column_name+'_icon').html('<i class="fas fa-angle-down"></i>');
        }
        $('#hidden_column_name').val(column_name);
        $('#hidden_sort_type').val(reverse_order);
        var page = $('#hidden_page').val();
        var query = $('#serach').val();
        fetch_data(page, reverse_order, column_name, query);
    });

    $(document).on('click', '.pagination a', function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        var column_name = $('#hidden_column_name').val();
        var sort_type = $('#hidden_sort_type').val();

        var query = $('#serach').val();

        $('li').removeClass('active');
            $(this).parent().addClass('active');
        fetch_data(page, sort_type, column_name, query);
    });
});
</script>

    <script>
        function editservicetype(factory_id, factory_name){
            $("#factory_id").val(factory_id);
            $("#factory_name").val(factory_name);
        }
        $(function () {
            $("#example1").DataTable();
        });
    </script>
@endsection