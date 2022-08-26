        @php ($no = 0)
        @foreach ($factory_data as $factory)
        <tr>
            <td>{{$factory->factory_code}}</td>
            <td>{{$factory->factory_name}}</td>
            <td>{{$factory->factory_company_name}}</td>
            <td>{{$factory->factory_company_address}}</td>
            <td>{{$factory->factory_email}}</td>
            @if (RoleAccess::whereMenu(29) > 0 && RoleAccess::whereMenu(29) < 3)
            <th>
                <a href="{{ url('factory/update/'.Crypt::encrypt($factory->factory_id)) }}"><i class="fas fa-edit text-orange"></i></a>

                @if (RoleAccess::whereMenu(29) == 1)
                <a href="{{ url('factory/delete/'.Crypt::encrypt($factory->factory_id)) }}" onclick="return confirm('Apakah anda yakin ingin menghapus?')"><i class="fas fa-trash text-danger"></i></a>
                @endif
            </th>
            @endif
        </tr>
        @endforeach
        <tr>
            <td colspan="5">
                <div class="row">
                    <div class="col-md-6">
                        Showing  {{ $factory_data->firstItem() }} to {{ $factory_data->lastItem() }} of {{ $factory_data->total() }} entries
                    </div>
                    <div class="col-md-6 float-right">
                        {!! $factory_data->links() !!}
                    </div>
                </div>
            </td>
        </tr>