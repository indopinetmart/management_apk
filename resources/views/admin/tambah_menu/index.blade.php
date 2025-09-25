@include('partials.header')

<div class="content-wrapper">
    <br>
    @include('partials.loader')
    <section class="content">
        <div class="container-fluid mt-2">

            <div class="card">
                <div class="card-header p-2" style="background-color: #000000FF;">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active btn btn-xs" href="#menu" data-toggle="tab">Tambah
                                Menu </a>
                        </li>
                        <li class="nav-item"><a class="nav-link btn btn-xs" href="#permissions" data-toggle="tab">Tambah
                                Permissions</a>
                        </li>

                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- / TAB-PAN TAMBAH MENU--->
                        <div class="active tab-pane" id="menu">
                            <div class="row"> {{-- Tambahkan row agar col-md-9 dan col-md-3 bisa sejajar --}}

                                {{-- Kiri col-9 --}}
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- Header --}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0"><i class="fas fa-bars"></i> Daftar Menu</h5>
                                                <div class="d-flex">
                                                    <!-- Tombol Tambah Menu -->
                                                    @if (auth()->user()->hasPermission('tambah_menu'))
                                                        <a href="" class="btn btn-sm tambah_menu"
                                                            style="background-color: #230060FF; color: #FFD700;">
                                                            <i class="fas fa-plus"></i> Tambah Menu
                                                        </a>
                                                    @endif

                                                </div>

                                            </div>

                                            {{-- Table Menu --}}
                                            <div class="table-responsive">
                                                <table id="tambah_menu" class="table table-bordered table-striped">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>No</th>
                                                            {{-- <th>ID</th> --}}
                                                            <th>Name</th>
                                                            <th>Icon</th>
                                                            <th>Route</th>
                                                            <th>Parent ID</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($menus as $menu)
                                                            <tr>
                                                                <td class="text-center">{{ $menu['index'] }}</td>
                                                                {{-- <td class="text-center">{{ $menu['id'] }}</td> --}}
                                                                <td class="text-center">{{ $menu['name'] }}</td>
                                                                <td class="text-center">{{ $menu['icon'] }}</td>
                                                                <td class="text-center">{{ $menu['route'] }}</td>
                                                                <td class="text-center">{{ $menu['parent_name'] }}</td>

                                                                <td class="text-center">

                                                                    @if (auth()->user()->hasPermission('edit_menu_setting'))
                                                                        <a href="#"
                                                                            class="btn btn-warning btn-xs mr-1 edit_menu"
                                                                            data-id="{{ $menu['id'] }}"
                                                                            title="Edit Menu">
                                                                            <i class="fas fa-edit"></i>
                                                                        </a>
                                                                    @endif

                                                                    @if (auth()->user()->hasPermission('hapus_menu'))
                                                                        <a href="#"
                                                                            class="btn btn-danger btn-xs delete_menu mr-1"
                                                                            data-id="{{ $menu['id'] }}"
                                                                            title="Hapus User">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </a>
                                                                    @endif
                                                                </td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center">Tidak ada data.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kanan col-3 --}}
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #6a0dad;">
                                            <h3 class="card-title" style="color: gold; font-weight: bold;">
                                                Role - Menu
                                            </h3>
                                        </div>
                                        <div class="card-body p-2">

                                            <div class="table-responsive">
                                                <table id="role_menu"
                                                    class="table table-sm table-bordered table-striped">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>Role Name</th>
                                                            <th>Menu Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($role_menus as $rm)
                                                            <tr>
                                                                <td class="text-center">{{ $rm['role_name'] }}</td>
                                                                <td class="text-center">{{ $rm['menu_name'] }}</td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="3" class="text-center">Tidak ada data.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div> {{-- Tutup row --}}
                        </div>
                        <!-- / END TAB-PAN TAMBAH MENU--->

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="permissions">
                            <div class="row"> {{-- Tambahkan row agar col-md-9 dan col-md-3 bisa sejajar --}}

                                {{-- Kiri col-9 --}}
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-body">
                                            {{-- Header --}}
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h5 class="mb-0"><i class="fas fa-key"></i> Daftar Permission</h5>
                                                <div class="d-flex">
                                                    <!-- Tombol Tambah Menu -->
                                                    @if (auth()->user()->hasPermission('tambah_permissions'))
                                                        <a href="" class="btn btn-sm tambah_permissions"
                                                            style="background-color: #230060FF; color: #FFD700;">
                                                            <i class="fas fa-plus"></i> Tambah Permissions
                                                        </a>
                                                    @endif
                                                </div>

                                            </div>

                                            {{-- Table Menu --}}
                                            <div class="table-responsive">
                                                <table id="table_permissions"
                                                    class="table table-bordered table-striped">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th>Description</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($permissions as $permissions)
                                                            <tr>
                                                                <td class="text text-center">{{ $permissions['index'] }}
                                                                </td>
                                                                <td class="text text-center">{{ $permissions['name'] }}
                                                                </td>
                                                                <td class="text text-center">
                                                                    {{ $permissions['description'] }}</td>
                                                                <td class="text text-center">
                                                                    @if (auth()->user()->hasPermission('edit_permissions'))
                                                                        <a href="#"
                                                                            class="btn btn-warning btn-xs mr-1 btn-edit-permissions"
                                                                            data-id="{{ $permissions['id'] }}"
                                                                            title="Edit User">
                                                                            <i class="fas fa-user-edit"></i>
                                                                        </a>
                                                                    @endif

                                                                    @if (auth()->user()->hasPermission('hapus_permissions'))
                                                                        <a href="#"
                                                                            class="btn btn-danger btn-xs btn-delete-permissions mr-1"
                                                                            data-id="{{ $permissions['id'] }}"
                                                                            title="Hapus User">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </a>
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="7" class="text-center">Tidak ada data
                                                                    .</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kanan col-3 --}}
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #6a0dad;">
                                            <h3 class="card-title" style="color: gold; font-weight: bold;">
                                                Role - Permissions
                                            </h3>
                                        </div>
                                        <div class="card-body p-2">

                                            <div class="table-responsive">
                                                <table id="role_permissions"
                                                    class="table table-sm table-bordered table-striped">
                                                    <thead class="text-center">
                                                        <tr>
                                                            <th>Role Name</th>
                                                            <th>Permission Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($role_permissions as $rp)
                                                            <tr>
                                                                <td class="text-center">{{ $rp['role_name'] }}</td>
                                                                <td class="text-center">{{ $rp['permission_name'] }}
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="2" class="text-center">Tidak ada data.
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div> {{-- Tutup row --}}
                        </div>


                    </div>
                    <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
        </div>
        <!-- /.card -->

</div>
</section>


<!-------///////////////// MODAL MENU/////////////////////////-------------->

<!-- /.Modal Tambah Menu -->
<div class="modal fade" id="tambahMenuModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Tambah Menu</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Menu*</label>
                            <input type="text" class="form-control mb-1" id="name_menu" name="name_menu"
                                placeholder="Nama Menu">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="icon">Icon*</label>
                            <input type="text" class="form-control mb-1" id="icon" name="icon"
                                placeholder="icone bisa menggunakan fontawesom, Iconic Icons, Ion Icons">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="parent_id">Parent_ID</label>
                            <select class="form form-control" name="parent_id" id="parent_id" style="width: 100%;">
                                <option value="">Pilih Parent_ID</option>
                                @foreach ($menus as $menu)
                                    <option value="{{ $menu['id'] }}">{{ ucfirst($menu['name']) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="route">Route*</label>
                            <input type="text" class="form-control mb-1" id="route" name="route"
                                placeholder="Routeing untuk Url Uniq">
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success simpan">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- /.End Modal Tambah Menu -->

<!-- / Modal Edit Menu -->
<div class="modal fade" id="editMenuModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Edit Data User</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="alert alert-danger d-none"></div>

                <!--content-->
                <div class="row">
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Menu*</label>
                            <input type="hidden" id="id" name="id">
                            <input type="text" class="form-control mb-1" id="name_menu_edit"
                                name="name_menu_edit" placeholder="Nama Menu">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="icon">Icon*</label>
                            <input type="text" class="form-control mb-1" id="icon_edit" name="icon_edit"
                                placeholder="icone bisa menggunakan fontawesom, Iconic Icons, Ion Icons">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="parent_id">Parent Menu</label>
                            <select class="form form-control" name="parent_id_edit" id="parent_id_edit"
                                style="width: 100%;">
                                <option value="">Pilih Parent Menu</option>

                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label for="route">Route*</label>
                            <input type="text" class="form-control mb-1" id="route_edit" name="route_edit"
                                placeholder="Routeing untuk Url Uniq">
                        </div>
                    </div>

                </div>
                <!--/. content-->
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success edit">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.End Modal Edit Menu  -->

<!-- Modal Delete Menu -->
<div class="modal fade" id="delete-menu" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Hapus Data User</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus menu berikut ini?</p>

                <input type="hidden" id="id">

                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" class="form-control" id="name_delete" disabled>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger hapus">Hapus</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Menu -->

<!-------///////////////// END MODAL MENU/////////////////////////-------------->



<!-------///////////////// MODAL PERMISSIONS MENU/////////////////////////-------------->


<!-- /.Modal Tambah Menu -->
<div class="modal fade" id="tambahPermissionsModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Tambah Permissions</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Nama </label>
                    <input type="text" class="form-control" id="name_permissions" name="name_permissions">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" id="description" name="description">
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success simpan">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- /.End Modal Tambah Menu -->


<!-- / Modal Edit Menu -->
<div class="modal fade" id="editPermissionsModal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Edit Data Permissions</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama </label>
                    <input type="hidden" id="id" name="id">
                    <input type="text" class="form-control" id="name_permissions_edit"
                        name="name_permissions_edit">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" id="description_edit" name="description_edit">
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success btn-save-permissions">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.End Modal Edit Menu  -->


<div class="modal fade" id="delete-permissions" tabindex="-1" role="dialog" aria-labelledby="deleteUserLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header " style="background-color: #6a0dad;">
                <h6 class="modal-title" style="color: gold; font-weight: bold;">Hapus Data User</h6>
                <button type="button" class="close" style="color: gold; font-weight: bold;" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus permissions berikut ini?</p>

                <input type="hidden" id="id">

                <div class="form-group">
                    <label>Nama Menu</label>
                    <input type="text" class="form-control" id="name_delete_permissions" disabled>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-hapus-permissions">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-------///////////////// END MODAL ROLE MENU/////////////////////////-------------->


</div>

@include('partials.script')
@include('admin.tambah_menu.script_tambahmenu')
